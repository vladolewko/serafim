<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NovaPostService
{
    private string $apiKey;
    private array $requiredSenderVars = [
        'NOVA_POST_CITY_SENDER',
        'NOVA_POST_SENDER_REF',
        'NOVA_POST_SENDER_ADDRESS',
        'NOVA_POST_CONTACT_SENDER',
        'NOVA_POST_SENDER_PHONE'
    ];

    public function __construct()
    {
        $this->apiKey = env('NOVA_POST_API_KEY');
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
     * Перетворення warehouse ref Нової Пошти в текстову назву
     */
    public function convertNovaPoshtaWarehouseRef(string $warehouseRef): string
    {
        try {
            // Отримуємо дані про відділення
            $response = $this->makeRequest('Address', 'getWarehouses', [
                'Ref' => $warehouseRef,
                'Language' => 'UA'
            ]);

            if (empty($response['data'][0])) {
                return "Відділення: {$warehouseRef}";
            }

            $warehouse = $response['data'][0];

            // Отримуємо назву міста
            $cityResponse = $this->makeRequest('Address', 'getCities', [
                'Ref' => $warehouse['CityRef'],
                'Language' => 'UA'
            ]);

            $cityName = $cityResponse['data'][0]['Description'] ?? 'Невідоме місто';

            // Формуємо результат
            $result = "м. {$cityName}, {$warehouse['Description']}";

            if (!empty($warehouse['ShortAddress'])) {
                $result .= " ({$warehouse['ShortAddress']})";
            }

            return $result;

        } catch (Exception $e) {
            Log::error('Failed to convert Nova Poshta warehouse ref: ' . $e->getMessage());
            return "Відділення: {$warehouseRef}";
        }
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

        if (empty($cart['product']) || empty($cart['quantity'])) {
            return $warehouses;
        }

        $quantity = $cart['quantity'];
        $product = is_object($cart['product']) ? $cart['product'] : (object)$cart['product'];

        // Розрахунок параметрів
        $totalWeight = ($product->weight ?? 0) * $quantity;
        $volumeWeight = $this->calculateVolumeWeight($product->dimension ?? '', $quantity);
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
                        $maxDim = max((float)$dims[0], (float)$dims[1], (float)$dims[2]);
                        if ($maxDim > 60 || (float)$dims[0] > 40 || (float)$dims[1] > 30) {
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
     * Отримання відділень з фільтрацією тільки поштоматів за кількістю
     */
    public function getWarehousesWithQuantityFilter(string $settlementRef, int $quantity): array
    {
        $warehouses = $this->getWarehousesWithRestrictions($settlementRef);

        // Фільтруємо тільки поштомати якщо кількість > 1
        if ($quantity <= 1) {
            return $warehouses;
        }

        return array_filter($warehouses, function($warehouse) {
            $warehouseType = $warehouse['TypeOfWarehouse'] ?? 'Branch';
            return $warehouseType !== 'PostBox'; // Виключаємо поштомати
        });
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
     * Створення контрагента (отримувача)
     */
    public function createCounterparty(array $data): array
    {
        $response = $this->makeRequest('CounterpartyGeneral', 'save', [
            'FirstName' => $data['name'],
            'MiddleName' => '',
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
            'Email' => $data['email'] ?? '',
            'CounterpartyType' => 'PrivatePerson',
            'CounterpartyProperty' => 'Recipient',
        ]);

        if (!isset($response['data'][0])) {
            throw new \Exception('Помилка створення контрагента');
        }

        $counterpartyData = $response['data'][0];
        $counterpartyRef = $counterpartyData['Ref'];
        $contactPersonRef = $counterpartyData['ContactPerson']['data'][0]['Ref'] ?? null;

        // Якщо контактна особа не створилась автоматично
        if (!$contactPersonRef) {
            $contactResponse = $this->createContactPerson($counterpartyRef, $data);
            $contactPersonRef = $contactResponse['data'][0]['Ref'] ?? null;
        }

        return array_merge($counterpartyData, [
            'Ref' => $counterpartyRef,
            'ContactPersonRef' => $contactPersonRef,
        ]);
    }

    /**
     * Створення контактної особи
     */
    public function createContactPerson(string $counterpartyRef, array $data): array
    {
        return $this->makeRequest('ContactPersonGeneral', 'save', [
            'CounterpartyRef' => $counterpartyRef,
            'FirstName' => $data['name'],
            'MiddleName' => '',
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
        ]);
    }

    /**
     * Створення експрес-накладної (ТТН)
     */
    public function createTTN(array $data, $cart, string $payment): array
    {
        try {
            $this->validateSenderConfiguration();

            $product = $cart['product'];
            $quantity = $cart['quantity'] ?? 1;
            $productData = is_object($product) ? $product : (object)$product;

            // Отримання контактної особи отримувача
            $contactRecipient = $data['contact_person_ref'] ?? $this->getContactPerson($data['counterparty_ref']);
            if (!$contactRecipient) {
                throw new \Exception('Не вдалося отримати контактну особу отримувача');
            }

            // Розрахунок габаритів та ваги
            $actualWeight = ($productData->weight ?? 0) * $quantity;
            $volumeWeight = $this->calculateVolumeWeight($productData->dimension ?? '', $quantity);
            $totalWeight = max($actualWeight, $volumeWeight); // Це йде в Weight
            $totalVolume = $this->calculateTotalVolumeInM3($productData->dimension ?? '', $quantity); // Це йде в VolumeGeneral

            // Отримання інформації про відділення та валідація обмежень
            $warehouseInfo = $this->getWarehouseInfo($data['warehouse']);
            $this->validateWarehouseRestrictions($warehouseInfo, $totalWeight, $totalVolume, $quantity);

            // Визначення типу сервісу
            $serviceType = $this->determineServiceType($data['warehouse'], $warehouseInfo);
            $isPostBox = $serviceType === 'WarehouseDoors';

            // Базові дані ТТН
            $ttnData = [
                'PayerType' => 'Sender',
                'PaymentMethod' => 'Cash',
                'DateTime' => now()->addDay()->format('d.m.Y'),
                'CargoType' => $totalWeight <= 2 ? 'Parcel' : 'Cargo',
                'VolumeGeneral' => (string)$totalVolume,
                'Weight' => (string)$totalWeight,
                'ServiceType' => $serviceType,
                'SeatsAmount' => $isPostBox ? '1' : (string)$quantity,
                'Description' => $this->generateDescription($productData, $quantity),

                // Відправник
                'CitySender' => env('NOVA_POST_CITY_SENDER'),
                'Sender' => env('NOVA_POST_SENDER_REF'),
                'SenderAddress' => env('NOVA_POST_SENDER_ADDRESS'),
                'ContactSender' => env('NOVA_POST_CONTACT_SENDER'),
                'SendersPhone' => env('NOVA_POST_SENDER_PHONE'),

                // Отримувач
                'CityRecipient' => $data['settlement'],
                'Recipient' => $data['counterparty_ref'],
                'RecipientAddress' => $data['warehouse'],
                'ContactRecipient' => $contactRecipient,
                'RecipientsPhone' => $data['phone'],
            ];

            $categoryOfWarehouse = $warehouseInfo['CategoryOfWarehouse'] ?? '';
            if ($categoryOfWarehouse === 'Postomat') {
                $dimensions = $productData->dimension ?? '';
                if (!empty($dimensions)) {
                    $optionsSeat = [
                        [
                            'volumetricWidth' => $this->getDimensionFromString($productData->dimension ?? '', 0),
                            'volumetricLength' => $this->getDimensionFromString($productData->dimension ?? '', 1),
                            'volumetricHeight' => $this->getDimensionFromString($productData->dimension ?? '', 2),
                            'weight' => (string)$totalWeight
                        ]
                    ];
                    Log::info('OptionsSeat debug', $optionsSeat);
                    $ttnData['OptionsSeat'] = $optionsSeat;
                } else {
                    // Якщо габаритів немає - використовуємо стандартні
                    $ttnData['OptionsSeat'] = [
                        [
                            'volumetricWidth' => 20,
                            'volumetricLength' => 20,
                            'volumetricHeight' => 20,
                            'weight' => (string)$totalWeight
                        ]
                    ];
                }
            }

            // Накладений платіж для готівкової оплати
            if ($payment === 'cash') {
                $cartTotal = is_array($cart) ? $cart['total'] : $cart->total;
                $ttnData['BackwardDeliveryData'] = [[
                    'PayerType' => 'Recipient',
                    'CargoType' => 'Money',
                    'RedeliveryString' => (string)$cartTotal,
                ]];
            }

            $response = $this->makeRequest('InternetDocumentGeneral', 'save', $ttnData);

            if (!isset($response['data'][0])) {
                $errors = $response['errors'] ?? ['Невідома помилка створення ТТН'];
                throw new \Exception('Помилка створення ТТН: ' . implode(', ', $errors));
            }

            return $response['data'][0];

        } catch (\Exception $e) {
            Log::error('TTN creation failed', [
                'message' => $e->getMessage(),
                'payment' => $payment,
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Отримання розміру з рядка габаритів
     */
    private function getDimensionFromString(string $dimensions, int $index): float
    {
        if (empty($dimensions)) {
            return 20;
        }

        $dimensionParts = preg_split('/\s+на\s+/i', trim($dimensions));

        if (count($dimensionParts) > $index) {
            return (float)trim($dimensionParts[$index]);
        }

        return 20;
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
     * Налаштування відправника
     */
    public function setupSender(array $data): bool
    {
        try {
            // Створення/отримання відправника
            $senderRef = $this->createOrGetSender($data);
            if (!$senderRef) {
                throw new \Exception('Не вдалося створити/отримати відправника');
            }

            // Встановлення міста відправника
            $cityRef = $this->setCitySender($data['city']);
            if (!$cityRef) {
                throw new \Exception('Не вдалося знайти місто відправника');
            }

            // Пошук населеного пункту та отримання адреси
            $senderSettlement = $this->searchSettlement($data['city']);
            if (empty($senderSettlement)) {
                throw new \Exception('Не вдалося знайти населений пункт');
            }

            $senderAddress = $this->getSenderAddress($senderSettlement[0]['Ref']);
            if (!$senderAddress) {
                throw new \Exception('Не вдалося отримати адресу відправника');
            }

            // Отримання контактної особи
            $contactSender = $this->getContactPerson($senderRef);
            if (!$contactSender) {
                throw new \Exception('Не вдалося отримати контактну особу відправника');
            }

            // Оновлення змінних оточення
            $this->updateEnvVariables([
                'NOVA_POST_CITY_SENDER' => $cityRef,
                'NOVA_POST_SENDER_REF' => $senderRef,
                'NOVA_POST_SENDER_ADDRESS' => $senderAddress,
                'NOVA_POST_CONTACT_SENDER' => $contactSender,
                'NOVA_POST_SENDER_PHONE' => $data['phone']
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Sender setup failed', [
                'message' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Перевірка налаштувань відправника
     */
    public function checkSenderSetup(): array
    {
        $labels = [
            'NOVA_POST_CITY_SENDER' => 'Місто відправника',
            'NOVA_POST_SENDER_REF' => 'Відправник',
            'NOVA_POST_SENDER_ADDRESS' => 'Адреса(відділення) відправника',
            'NOVA_POST_CONTACT_SENDER' => 'Контактна особа відправника',
            'NOVA_POST_SENDER_PHONE' => 'Телефон відправника'
        ];

        $missing = [];
        $existing = [];

        foreach ($labels as $var => $name) {
            $value = env($var);
            if (empty($value)) {
                $missing[] = $name;
            } else {
                $existing[$name] = $value;
            }
        }

        return [
            'is_configured' => empty($missing),
            'missing' => $missing,
            'existing' => $existing
        ];
    }

    /**
     * Тестування API ключа
     */
    public function testApiKey(): array
    {
        try {
            $response = $this->makeRequest('Address', 'getCities', [
                'FindByString' => 'Київ',
                'Limit' => 1
            ]);

            return [
                'success' => true,
                'message' => 'API ключ працює коректно',
                'data' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Помилка API ключа: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Валідація налаштувань відправника
     */
    private function validateSenderConfiguration(): void
    {
        foreach ($this->requiredSenderVars as $var) {
            if (empty(env($var))) {
                throw new \Exception("Не налаштована змінна оточення: {$var}. Спочатку налаштуйте відправника.");
            }
        }
    }

//    /**
//     * Розрахунок загального об'єму
//     */
//    private function calculateTotalVolume(string $dimensions, int $quantity): float
//    {
//        if (empty($dimensions)) {
//            return 0.004 * $quantity; // стандартний об'єм НП
//        }
//
//        $dimensionParts = preg_split('/\s+на\s+/i', trim($dimensions));
//
//        if (count($dimensionParts) !== 3) {
//            return 0.004 * $quantity;
//        }
//
//        $length = (float)trim($dimensionParts[0]);  // в см
//        $width = (float)trim($dimensionParts[1]);   // в см
//        $height = (float)trim($dimensionParts[2]);  // в см
//
//        // Об'ємна вага НП = (L x W x H) / 4000
//        $volumeWeightPerItem = ($length * $width * $height) / 4000;
//
//        return $volumeWeightPerItem * $quantity;
//    }

    /**
     * Генерація опису товару
     */
    private function generateDescription(object $product, int $quantity): string
    {
        $productName = $product->name ?? 'Товар';
        $baseDescription = "Замовлення з інтернет-магазину: {$productName}";

        return $quantity > 1 ? "{$baseDescription} x{$quantity}" : $baseDescription;
    }

    /**
     * Розрахунок об'ємної ваги за стандартами НП
     */
    private function calculateVolumeWeight(string $dimensions, int $quantity): float
    {
        if (empty($dimensions)) {
            return 1 * $quantity; // мінімальна об'ємна вага
        }

        $dimensionParts = preg_split('/\s+на\s+/i', trim($dimensions));

        if (count($dimensionParts) !== 3) {
            return 1 * $quantity;
        }

        $length = (float)trim($dimensionParts[0]);
        $width = (float)trim($dimensionParts[1]);
        $height = (float)trim($dimensionParts[2]);

        // Об'ємна вага = (L x W x H в см³) / 4000
        $volumeWeightPerItem = ($length * $width * $height) / 4000;

        return max($volumeWeightPerItem * $quantity, 0.1); // мінімум 0.1 кг
    }

    /**
     * Розрахунок об'єму в м³ для API
     */
    private function calculateTotalVolumeInM3(string $dimensions, int $quantity): float
    {
        if (empty($dimensions)) {
            return 0.004 * $quantity; // 4 літри стандарт
        }

        $dimensionParts = preg_split('/\s+на\s+/i', trim($dimensions));

        if (count($dimensionParts) !== 3) {
            return 0.004 * $quantity;
        }

        $length = (float)trim($dimensionParts[0]) / 100;  // з см в метри
        $width = (float)trim($dimensionParts[1]) / 100;   // з см в метри
        $height = (float)trim($dimensionParts[2]) / 100;  // з см в метри

        $volumePerItem = $length * $width * $height; // в м³
        return $volumePerItem * $quantity;
    }

    /**
     * Валідація обмежень відділення/поштомату
     */
    private function validateWarehouseRestrictions(?array $warehouseInfo, float $totalWeight, float $totalVolume, int $quantity): void
    {
        if (!$warehouseInfo) {
            return;
        }

        $warehouseType = $warehouseInfo['TypeOfWarehouse'] ?? 'Branch';
        $maxWeight = (float)($warehouseInfo['TotalMaxWeightAllowed'] ?? 0);

        // Перевірка для поштоматів
        if ($warehouseType === 'PostBox') {
            if ($quantity > 1) {
                throw new \Exception('Для доставки до поштоматів можна відправити лише 1 товар.');
            }

            // Жорстке обмеження для поштоматів - 20кг
            if ($totalWeight > 20) {
                throw new \Exception('Вага товару перевищує максимально дозволену для поштоматів (20 кг).');
            }
        }

        // Перевірка API обмежень тільки якщо вони є і більше 0
        if ($maxWeight > 0 && $totalWeight > $maxWeight) {
            $warehouseName = $warehouseType === 'PostBox' ? 'поштомату' : 'відділення';
            throw new \Exception("Вага відправлення ({$totalWeight} кг) перевищує максимально дозволену для цього {$warehouseName} ({$maxWeight} кг).");
        }
    }

    /**
     * Визначення типу сервісу доставки
     */
    private function determineServiceType(string $warehouseRef, ?array $warehouseInfo = null): string
    {
        try {
            if (!$warehouseInfo) {
                $warehouseInfo = $this->getWarehouseInfo($warehouseRef);
            }

            if ($warehouseInfo &&
                isset($warehouseInfo['TypeOfWarehouse']) &&
                $warehouseInfo['TypeOfWarehouse'] === 'PostBox') {
                return 'WarehouseDoors';
            }

            return 'WarehouseWarehouse';
        } catch (\Exception $e) {
            return 'WarehouseWarehouse';
        }
    }

    /**
     * Отримання інформації про відділення/поштомат
     */
    private function getWarehouseInfo(string $warehouseRef): ?array
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
     * Встановлення міста відправника
     */
    private function setCitySender(string $cityName): ?string
    {
        $response = $this->makeRequest('Address', 'getCities', [
            'FindByString' => $cityName,
        ]);

        return $response['data'][0]['Ref'] ?? null;
    }

    /**
     * Створення або отримання відправника
     */
    private function createOrGetSender(array $data): ?string
    {
        // Спочатку перевіряємо наявних відправників
        $response = $this->makeRequest('Counterparty', 'getCounterparties', [
            'CounterpartyProperty' => 'Sender'
        ]);

        if (isset($response['data'][0])) {
            return $response['data'][0]['Ref'];
        }

        // Створюємо нового відправника
        $cityRef = $this->setCitySender($data['city']);
        if (!$cityRef) {
            throw new \Exception('Не вдалося знайти місто відправника');
        }

        $response = $this->makeRequest('Counterparty', 'save', [
            'CityRef' => $cityRef,
            'FirstName' => $data['name'],
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
            'Email' => $data['email'] ?? '',
            'CounterpartyType' => 'PrivatePerson',
            'CounterpartyProperty' => 'Sender'
        ]);

        $senderRef = $response['data'][0]['Ref'] ?? null;

        // Створюємо контактну особу для відправника
        if ($senderRef) {
            $this->makeRequest('ContactPersonGeneral', 'save', [
                'CounterpartyRef' => $senderRef,
                'FirstName' => $data['name'],
                'MiddleName' => '',
                'LastName' => $data['surname'],
                'Phone' => $data['phone'],
            ]);
        }

        return $senderRef;
    }

    /**
     * Отримання адреси відправника
     */
    private function getSenderAddress(string $cityRef): string
    {
        $response = $this->makeRequest('AddressGeneral', 'getWarehouses', [
            'SettlementRef' => $cityRef,
        ]);

        if (isset($response['data'][0]['Ref'])) {
            return $response['data'][0]['Ref'];
        }

        throw new \Exception('Не вдалося знайти відділення для створення адреси відправника');
    }

    /**
     * Отримання контактної особи
     */
    private function getContactPerson(string $counterpartyRef): ?string
    {
        $response = $this->makeRequest('CounterpartyGeneral', 'getCounterpartyContactPersons', [
            'Ref' => $counterpartyRef,
        ]);

        return $response['data'][0]['Ref'] ?? null;
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

    /**
     * Оновлення змінних оточення
     */
    private function updateEnvVariables(array $variables): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($variables as $key => $value) {
            if (strpos($envContent, $key) !== false) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
