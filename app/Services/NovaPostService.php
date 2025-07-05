<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NovaPostService
{
    private string $apiKey;
    private ProductService $productService;
//    private array $requiredSenderVars = [
//        'NOVA_POST_CITY_SENDER',
//        'NOVA_POST_SENDER_REF',
//        'NOVA_POST_SENDER_ADDRESS',
//        'NOVA_POST_CONTACT_SENDER',
//        'NOVA_POST_SENDER_PHONE'
//    ];

    public function __construct(ProductService $productService)
    {
        $this->apiKey = env('NOVA_POST_API_KEY');
        $this->productService = $productService;
    }

    /**
     * Пошук населених пунктів
     */
    public function searchSettlement(string $search): array
    {
        $response = $this->makeRequest('AddressGeneral', 'searchSettlements', [
            'CityName' => $search,
            'Limit' => '500',
            'Page' => '1'
        ]);

        return $response['data'][0]['Addresses'] ?? [];
    }

    /**
     * Отримання відділень/поштоматів з інформацією про обмеження
     */
    public function getWarehousesWithRestrictions(string $settlementRef): array
    {
        $warehouses = $this->getWarehouses($settlementRef);

        foreach ($warehouses as &$warehouse) {
            $warehouse['restrictions'] = [
                'max_weight' => (float)($warehouse['TotalMaxWeightAllowed'] ?? 0),
                'max_volume' => (float)($warehouse['MaxVolumeAllowed'] ?? 0),
                'is_postbox' => ($warehouse['TypeOfWarehouse'] ?? 'Branch') === 'PostBox',
                'postbox_only_single_item' => ($warehouse['TypeOfWarehouse'] ?? 'Branch') === 'PostBox'
            ];
        }

        return $warehouses;
    }

    /**
     * Отримання відфільтрованих відділень з урахуванням обмежень товару
     */
    public function getFilteredWarehouses(string $settlementRef, array $cart): array
    {
        $warehouses = $this->getWarehousesWithRestrictions($settlementRef);
        $product = $this->productService->getById($cart['productId']);

        if (!$product || !$cart['quantity']) {
            return $warehouses;
        }

        $quantity = $cart['quantity'];
        // Розрахунок параметрів
        $totalWeight = ($product->weight ?? 0) * $quantity;
        $volumeWeight = $this->calculateVolumeWeight($product->length, $product->height, $product->width, $quantity);
        $finalWeight = max($totalWeight, $volumeWeight);
        $cargoType = $finalWeight <= 2 ? 'Parcel' : 'Cargo';

        // Якщо кількість > 1, виключаємо всі поштомати та залишаємо тільки відділення до 30кг і більше
        if ($quantity > 1) {
            $warehouses = array_filter($warehouses, function($warehouse) {
                $categoryOfWarehouse = $warehouse['CategoryOfWarehouse'] ?? '';
                $warehouseType = $warehouse['WarehouseType'] ?? '';
                $typeOfWarehouse = $warehouse['TypeOfWarehouse'] ?? 'Branch';
                $maxWeight = (float)($warehouse['TotalMaxWeightAllowed'] ?? 0);

                // Виключаємо поштомати
                if ($categoryOfWarehouse === 'Postomat' || $typeOfWarehouse === 'PostBox') {
                    return false;
                }

                // Виключаємо Drop-Off відділення
                if ($warehouseType === 'DropOff') {
                    return false;
                }

                // Залишаємо тільки відділення з максимальною вагою 30кг і більше
                // Якщо maxWeight = 0, то це означає необмежену вагу, тому залишаємо
                if ($maxWeight > 0 && $maxWeight < 30) {
                    return false;
                }

                return true;
            });
        }

        $filteredWarehouses = [];

        foreach ($warehouses as $warehouse) {
            $warehouseType = $warehouse['TypeOfWarehouse'] ?? 'Branch';
            $maxWeight = (float)($warehouse['TotalMaxWeightAllowed'] ?? 0);

            // Жорсткі обмеження для поштоматів
            if ($warehouseType === 'PostBox') {
                // 1. Тільки 1 товар
                if ($quantity > 1) {
                    continue;
                }

                // 2. Вага до 20кг
                if ($finalWeight > 20) {
                    continue;
                }

                // 3. Габарити 40x30x60см
                if (!empty($product->dimension)) {

                    $dims = preg_split('/\s+на\s+/i', trim($product->dimension));
                    if (count($dims) === 3) {
                        $maxDim = max($product->length, $product->heigth, $product->width);
                        if ($maxDim > 60 || $product->length > 40 || $product->height > 30) {
                            continue;
                        }
                    }
                }
            }

            // Якщо це Drop-Off відділення і вантаж важкий - пропускаємо
            if ($maxWeight > 0 && $maxWeight <= 10 && $cargoType === 'Cargo') {
                continue; // Drop-Off відділення не приймають важкий вантаж
            }

            // Для відділень використовуємо API дані, але з обережністю
            // Якщо API повернув обмеження і воно менше фактичної ваги
            if ($maxWeight > 0 && $finalWeight > $maxWeight) {
                continue;
            }

            $filteredWarehouses[] = $warehouse;
        }

        return $filteredWarehouses;
    }


    /**
     * Отримання відділень/поштоматів
     */
    public function getWarehouses(string $settlementRef): array
    {
        $response = $this->makeRequest('AddressGeneral', 'getWarehouses', [
            'SettlementRef' => $settlementRef,
        ]);

        return $response['data'] ?? [];
    }

    /**
     * Розрахунок вартості доставки
     */
    public function getServiceCosts(string $recipientCityRef, float $weight, float $total, int $quantity = 1): float
    {
        $response = $this->makeRequest('InternetDocument', 'getDocumentPrice', [
            'CitySender' => env('NOVA_POST_CITY_SENDER'),
            'CityRecipient' => $recipientCityRef,
            'Weight' => (string)$weight,
            'ServiceType' => 'WarehouseWarehouse',
            'CargoType' => 'Cargo',
            'SeatsAmount' => $quantity,
            'RedeliveryCalculate' => [
                'CargoType' => 'Money',
                'Amount' => $total
            ]
        ]);

        $data = $response['data'][0];
        return $data['Cost'] + $data['CostRedelivery'];
    }

    /**
     * Розрахунок об'ємної ваги за стандартами НП
     */
    private function calculateVolumeWeight($length, $height, $width, int $quantity): float
    {
        $length = (float)$length;
        $width = (float)$width;
        $height = (float)$height;

        // Перевірка на валідність габаритів
        if ($length <= 0 || $width <= 0 || $height <= 0) {
            return 0.1; // мінімальна вага
        }

        // Об'ємна вага = (L x W x H в см³) / 4000
        $volumeWeightPerItem = ($length * $width * $height) / 4000;

        return max($volumeWeightPerItem * $quantity, 0.1); // мінімум 0.1 кг
    }

    /**
     * Отримання інформації про відділення/поштомат
     */
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

    /**
     * Виконання API запиту до Нової Пошти
     */
    private function makeRequest(string $modelName, string $calledMethod, array $methodProperties = []): array
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
}
