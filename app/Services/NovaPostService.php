<?php

namespace App\Services;

use App\Models\NovaPoshtaSettlement;
use App\Models\NovaPoshtaWarehouse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NovaPostService
{
    private const DEFAULT_SENDER_CITY = 'e718a680-4b33-11e4-ab6d-005056801329';
    private const MIN_WEIGHT = 0.5;
    private const PARCEL_WEIGHT_LIMIT = 30;
    private const POSTBOX_WEIGHT_LIMIT = 20;
    private const POSTBOX_MAX_DIMENSION = 60;
    private const POSTBOX_MAX_LENGTH = 40;
    private const POSTBOX_MAX_HEIGHT = 30;
    private const VOLUME_WEIGHT_DIVISOR = 4000;

    private string $apiKey;
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->apiKey = config('services.nova_post.api_key');
        $this->productService = $productService;
    }

    public function searchSettlement(string $search, int $page = 1, int $perPage = 50): array
    {
        $searchLower = mb_strtolower(trim($search));

        $totalQuery = NovaPoshtaSettlement::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('description', 'LIKE', "%{$search}%")
                    ->orWhere('description_ru', 'LIKE', "%{$search}%");
            });

        $total = $totalQuery->count();

        $settlements = NovaPoshtaSettlement::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('description', 'LIKE', "%{$search}%")
                    ->orWhere('description_ru', 'LIKE', "%{$search}%");
            })
            ->orderByRaw($this->buildSettlementSortQuery(), ["{$searchLower}%", "{$searchLower}%"])
            ->orderBy('api_warehouses_count', 'desc')
            ->orderBy('description')
            ->get()
            ->map(fn($settlement) => $this->mapSettlementData($settlement));

        $offset = ($page - 1) * $perPage;
        $paginatedSettlements = $settlements->slice($offset, $perPage)->values()->toArray();

        return [
            'settlements' => $paginatedSettlements,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'has_more' => $page < ceil($total / $perPage)
            ]
        ];
    }

    public function getFilteredWarehouses(string $settlementRef, array $cart, int $page = 1, int $perPage = 30, string $search = ''): array
    {
        $allWarehouses = $this->getAllWarehousesForFiltering($settlementRef);
        $product = $this->productService->getById($cart['productId']);

        if (!$product || !$cart['quantity']) {
            $warehouses = $allWarehouses;
        } else {
            $quantity = $cart['quantity'];
            $totalWeight = ($product->weight ?? 0) * $quantity;
            $volumeWeight = $this->calculateVolumeWeight($product->length, $product->height, $product->width, $quantity);
            $finalWeight = max($totalWeight, $volumeWeight);

            $warehouses = $this->filterWarehousesByProduct($allWarehouses, $product, $quantity, $finalWeight);
        }

        // Застосовуємо пошук якщо він є
        if (!empty($search)) {
            $warehouses = $this->searchWarehouses($warehouses, $search);
        }

        $this->sortWarehouses($warehouses);

        return $this->paginateArray($warehouses, $page, $perPage);
    }

    /**
     * Пошук відділень за назвою та адресою
     */
    private function searchWarehouses(array $warehouses, string $search): array
    {
        $search = mb_strtolower(trim($search));

        if (empty($search)) {
            return $warehouses;
        }

        return array_filter($warehouses, function($warehouse) use ($search) {
            $description = mb_strtolower($warehouse['Description'] ?? '');
            $shortAddress = mb_strtolower($warehouse['ShortAddress'] ?? '');
            $descriptionRu = mb_strtolower($warehouse['DescriptionRu'] ?? '');
            $shortAddressRu = mb_strtolower($warehouse['ShortAddressRu'] ?? '');

            // Шукаємо в українській та російській назвах і адресах
            return str_contains($description, $search) ||
                str_contains($shortAddress, $search) ||
                str_contains($descriptionRu, $search) ||
                str_contains($shortAddressRu, $search);
        });
    }

    public function getServiceCosts(string $recipientCityRef, float $weight, float $total, $product, int $quantity = 1): float
    {
        $volumeWeight = 0;
        if ($product && $product->length && $product->width && $product->height) {
            $volumeWeight = $this->calculateVolumeWeight($product->length, $product->height, $product->width, $quantity);
        }

        $finalWeight = max($weight, $volumeWeight, self::MIN_WEIGHT);
        $cargoType = $finalWeight <= self::PARCEL_WEIGHT_LIMIT ? 'Parcel' : 'Cargo';

        Log::info('Nova Poshta cost calculation request:', [
            'CitySender' => self::DEFAULT_SENDER_CITY,
            'CityRecipient' => $recipientCityRef,
            'Weight' => number_format($finalWeight, 1),
            'ServiceType' => 'WarehouseWarehouse',
            'CargoType' => $cargoType,
            'SeatsAmount' => (string)$quantity,
            'RedeliveryCalculate' => [
                'CargoType' => 'Money',
                'Amount' => number_format($total, 2)
            ]
        ]);

        $response = $this->makeRequest('InternetDocument', 'getDocumentPrice', [
            'CitySender' => self::DEFAULT_SENDER_CITY,
            'CityRecipient' => $recipientCityRef,
            'Weight' => number_format($finalWeight, 1),
            'Cost' => floor($total),
            'ServiceType' => 'WarehouseWarehouse',
            'CargoType' => $cargoType,
            'SeatsAmount' => (string)$quantity,
            'RedeliveryCalculate' => [
                'CargoType' => 'Money',
                'Amount' => floor($total)
            ]
        ]);

        $data = $response['data'][0];
        return (float)$data['Cost'] + (float)$data['CostRedelivery'];
    }

    public function getWarehouseInfo(string $warehouseRef): ?array
    {
        try {
            $response = $this->makeRequest('AddressGeneral', 'getWarehouses', [
                'Ref' => $warehouseRef
            ]);

            return $response['data'][0] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function makeRequest(string $modelName, string $calledMethod, array $methodProperties = []): array
    {
        try {
            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->withOptions(['verify' => false])
                ->post('https://api.novaposhta.ua/v2.0/json/', [
                    'apiKey' => $this->apiKey,
                    'modelName' => $modelName,
                    'calledMethod' => $calledMethod,
                    'methodProperties' => $methodProperties
                ]);

            if (!$response->successful()) {
                Log::error('Nova Poshta API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('API запит не вдався. HTTP код: ' . $response->status());
            }

            $data = $response->json();

            if (!empty($data['errors'])) {
                throw new \Exception('API помилка: ' . implode(', ', $data['errors']));
            }

            return $data;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception('Помилка з\'єднання з API Нової Пошти. Перевірте інтернет-з\'єднання.');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            throw new \Exception('Помилка запиту до API Нової Пошти.');
        }
    }

    private function buildSettlementSortQuery(): string
    {
        return "
            CASE
                WHEN LOWER(description) LIKE ? OR LOWER(description_ru) LIKE ? THEN 1
                ELSE 2
            END,
            CASE
                WHEN LOWER(settlement_type_description) LIKE '%місто%' THEN 1
                WHEN LOWER(settlement_type_description) LIKE '%селище міського типу%' OR LOWER(settlement_type_description) LIKE '%смт%' THEN 2
                WHEN LOWER(settlement_type_description) LIKE '%селище%' THEN 3
                ELSE 4
            END
        ";
    }

    private function mapSettlementData($settlement): array
    {
        $fullDescription = $this->formatFullSettlementName($settlement);
        $fullDescriptionRu = $this->formatFullSettlementName($settlement, 'ru');

        return [
            'Ref' => $settlement->ref,
            'DeliveryCity' => $settlement->ref,
            'MainDescription' => $fullDescription,
            'Description' => $fullDescription,
            'DescriptionRu' => $fullDescriptionRu,
            'Area' => $settlement->area_description,
            'AreaDescription' => $settlement->area_description,
            'AreaDescriptionRu' => $settlement->area_description_ru,
            'Region' => $settlement->region_description,
            'RegionDescription' => $settlement->region_description,
            'RegionDescriptionRu' => $settlement->region_description_ru,
            'SettlementType' => $settlement->settlement_type,
            'SettlementTypeDescription' => $settlement->settlement_type_description,
            'Delivery1' => $settlement->delivery,
            'Conglomerates' => $settlement->conglomerates,
            'Present' => $fullDescription,
            'Warehouses' => $settlement->api_warehouses_count ?? 0,
            'ParentRegionTypes' => $settlement->settlement_type,
            'ParentRegionCode' => $settlement->ref,
            'RegionTypes' => $settlement->settlement_type,
            'RegionTypesCode' => $settlement->settlement_type,
        ];
    }

    private function formatFullSettlementName($settlement, string $language = 'ua'): string
    {
        $parts = [];

        if ($language === 'ru') {
            $mainName = $settlement->description_ru ?? $settlement->description;
            $regionName = $settlement->region_description_ru;
            $areaName = $settlement->area_description_ru;
            $settlementTypeName = $settlement->settlement_type_description_ru ?? $settlement->settlement_type_description;
        } else {
            $mainName = $settlement->description;
            $regionName = $settlement->region_description;
            $areaName = $settlement->area_description . ' область';
            $settlementTypeName = $settlement->settlement_type_description;
        }

        if (!empty($settlementTypeName) && !str_contains(strtolower($mainName), strtolower($settlementTypeName))) {
            $parts[] = $settlementTypeName . ' ' . $mainName;
        } else {
            $parts[] = $mainName;
        }

        if (!empty($regionName) && $regionName !== $areaName) {
            $parts[] = $regionName;
        }

        if (!empty($areaName)) {
            $parts[] = $areaName;
        }

        return implode(', ', $parts);
    }

    private function getAllWarehousesForFiltering(string $settlementRef): array
    {
        return NovaPoshtaWarehouse::where('settlement_ref', $settlementRef)
            ->where('is_active', true)
            ->get()
            ->map(fn($warehouse) => $this->mapWarehouseData($warehouse))
            ->toArray();
    }

    private function mapWarehouseData($warehouse): array
    {
        return [
            'Ref' => $warehouse->ref,
            'SiteKey' => $warehouse->ref,
            'Description' => $warehouse->description,
            'DescriptionRu' => $warehouse->description_ru,
            'ShortAddress' => $warehouse->short_address,
            'ShortAddressRu' => $warehouse->short_address_ru,
            'Phone' => $warehouse->phone,
            'TypeOfWarehouse' => $warehouse->type_of_warehouse,
            'WarehouseType' => $warehouse->warehouse_type,
            'CategoryOfWarehouse' => $warehouse->category_of_warehouse,
            'TotalMaxWeightAllowed' => $warehouse->total_max_weight_allowed,
            'MaxVolumeAllowed' => $warehouse->max_volume_allowed,
            'PlaceMaxWeightAllowed' => $warehouse->place_max_weight_allowed,
            'DimensionsAllowed' => $warehouse->dimensions_allowed,
            'SettlementRef' => $warehouse->settlement_ref,
            'CityRef' => $warehouse->city_ref,
            'CityDescription' => $warehouse->city_description,
            'CityDescriptionRu' => $warehouse->city_description_ru,
            'Longitude' => $warehouse->longitude,
            'Latitude' => $warehouse->latitude,
            'PostFinance' => $warehouse->post_finance,
            'BicycleParking' => $warehouse->bicycle_parking,
            'PaymentAccess' => $warehouse->payment_access,
            'POSTerminal' => $warehouse->pos_terminal,
            'InternationalShipping' => $warehouse->international_shipping,
            'SelfServiceWorkplacesCount' => $warehouse->self_service_workplaces_count,
            'TotalMaxWeightAllowedDetails' => $warehouse->total_max_weight_allowed_details,
            'WorkInMobileAwis' => $warehouse->work_in_mobile_awis,
            'DirectDirection' => $warehouse->direct_direction,
            'ReturnDirection' => $warehouse->return_direction,
            'Reception' => $warehouse->reception,
            'Delivery' => $warehouse->delivery,
            'Schedule' => $warehouse->schedule,
            'DistrictCode' => $warehouse->district_code,
            'WarehouseStatus' => $warehouse->warehouse_status,
            'WarehouseStatusDate' => $warehouse->warehouse_status_date,
            'WarehouseIlluquidStatus' => $warehouse->warehouse_illiquid_status,
            'WarehouseIlluquidStatusDate' => $warehouse->warehouse_illiquid_status_date,
            'GeneratorEnabled' => $warehouse->generator_enabled,
            'MailOnly' => $warehouse->mail_only,
            'CopyWorkHours' => $warehouse->copy_work_hours,
            'ServicesFilter' => $warehouse->services_filter,
            'TypeOfRestrictions' => $warehouse->type_of_restrictions,
            'sort_priority' => $this->getWarehouseSortPriority($warehouse),
        ];
    }

    private function filterWarehousesByProduct(array $warehouses, $product, int $quantity, float $finalWeight): array
    {
        $cargoType = $finalWeight <= 2 ? 'Parcel' : 'Cargo';
        $filteredWarehouses = [];

        foreach ($warehouses as $warehouse) {
            if ($quantity > 1) {
                if ($this->shouldExcludeForMultipleQuantity($warehouse)) {
                    continue;
                }
            }

            $warehouseType = $warehouse['TypeOfWarehouse'] ?? 'Branch';
            $maxWeight = (float)($warehouse['TotalMaxWeightAllowed'] ?? 0);

            if ($warehouseType === 'PostBox') {
                if ($quantity > 1 || $finalWeight > self::POSTBOX_WEIGHT_LIMIT) {
                    continue;
                }

                if (!$this->isProductSuitableForPostBox($product)) {
                    continue;
                }
            }

            if ($maxWeight > 0 && $maxWeight <= 10 && $cargoType === 'Cargo') {
                continue;
            }

            if ($maxWeight > 0 && $finalWeight > $maxWeight) {
                continue;
            }

            $filteredWarehouses[] = $warehouse;
        }

        return $filteredWarehouses;
    }

    private function shouldExcludeForMultipleQuantity(array $warehouse): bool
    {
        $categoryOfWarehouse = $warehouse['CategoryOfWarehouse'] ?? '';
        $warehouseType = $warehouse['WarehouseType'] ?? '';
        $typeOfWarehouse = $warehouse['TypeOfWarehouse'] ?? 'Branch';
        $maxWeight = (float)($warehouse['TotalMaxWeightAllowed'] ?? 0);

        return $categoryOfWarehouse === 'Postomat' ||
            $typeOfWarehouse === 'PostBox' ||
            $warehouseType === 'DropOff' ||
            ($maxWeight > 0 && $maxWeight < 30);
    }

    private function isProductSuitableForPostBox($product): bool
    {
        if (empty($product->dimension)) {
            return true;
        }

        $dims = preg_split('/\s+на\s+/i', trim($product->dimension));
        if (count($dims) !== 3) {
            return true;
        }

        $maxDim = max($product->length, $product->height, $product->width);
        return $maxDim <= self::POSTBOX_MAX_DIMENSION &&
            $product->length <= self::POSTBOX_MAX_LENGTH &&
            $product->height <= self::POSTBOX_MAX_HEIGHT;
    }

    private function sortWarehouses(array &$warehouses): void
    {
        usort($warehouses, fn($a, $b) => $this->compareWarehouses($a, $b));
    }

    private function getWarehouseSortPriority($warehouse): int
    {
        $typeOfWarehouse = $warehouse->type_of_warehouse ?? 'Branch';
        $categoryOfWarehouse = $warehouse->category_of_warehouse ?? '';
        $warehouseType = $warehouse->warehouse_type ?? '';

        if ($typeOfWarehouse === 'Branch' && $warehouseType !== 'DropOff') {
            return 1;
        }

        if ($warehouseType === 'DropOff') {
            return 2;
        }

        if ($typeOfWarehouse === 'PostBox' || $categoryOfWarehouse === 'Postomat') {
            return 3;
        }

        return 1;
    }

    private function extractWarehouseNumber(string $description): int
    {
        if (preg_match('/№\s*(\d+)/', $description, $matches)) {
            return (int)$matches[1];
        }

        return 9999;
    }

    private function compareWarehouses($a, $b): int
    {
        $priorityA = $a['sort_priority'] ?? 1;
        $priorityB = $b['sort_priority'] ?? 1;

        if ($priorityA !== $priorityB) {
            return $priorityA <=> $priorityB;
        }

        if ($priorityA === 1) {
            $numberA = $this->extractWarehouseNumber($a['Description'] ?? '');
            $numberB = $this->extractWarehouseNumber($b['Description'] ?? '');

            if ($numberA !== $numberB) {
                return $numberA <=> $numberB;
            }
        }

        return strcmp($a['Description'] ?? '', $b['Description'] ?? '');
    }

    private function calculateVolumeWeight($length, $height, $width, $quantity): float
    {
        if (!$length || !$height || !$width) {
            return 0;
        }

        return ($length * $height * $width * $quantity) / self::VOLUME_WEIGHT_DIVISOR;
    }

    private function paginateArray(array $items, int $page, int $perPage): array
    {
        $total = count($items);
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($items, $offset, $perPage);

        return [
            'warehouses' => $paginatedItems,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'has_more' => $page < ceil($total / $perPage)
            ]
        ];
    }
}
