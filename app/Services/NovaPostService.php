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

            // Визначення типу сервісу та перевірка обмежень
            $serviceType = $this->determineServiceType($data['warehouse']);
            $isPostBox = $serviceType === 'WarehouseDoors';

            if ($isPostBox && $quantity > 1) {
                throw new \Exception('Для доставки до поштоматів можна відправити лише 1 товар. Оберіть відділення Нової Пошти або зменште кількість товарів.');
            }

            // Розрахунок габаритів та ваги
            $totalWeight = $productData->weight * $quantity;
            $totalVolume = $this->calculateTotalVolume($productData->dimension ?? '', $quantity);

            // Базові дані ТТН
            $ttnData = [
                'PayerType' => 'Sender',
                'PaymentMethod' => 'Cash',
                'DateTime' => now()->addDay()->format('d.m.Y'),
                'CargoType' => 'Cargo',
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

    /**
     * Розрахунок загального об'єму
     */
    private function calculateTotalVolume(string $dimensions, int $quantity): float
    {
        if (empty($dimensions)) {
            return 0.1 * $quantity;
        }

        $dimensionParts = explode('|', $dimensions);
        if (count($dimensionParts) !== 3) {
            return 0.1 * $quantity;
        }

        $length = (float)$dimensionParts[0] / 100;
        $width = (float)$dimensionParts[1] / 100;
        $height = (float)$dimensionParts[2] / 100;

        $volumePerItem = $length * $width * $height;
        $totalVolume = $volumePerItem * $quantity;

        return max($totalVolume, 0.1);
    }

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
     * Визначення типу сервісу доставки
     */
    private function determineServiceType(string $warehouseRef): string
    {
        try {
            $warehouseInfo = $this->getWarehouseInfo($warehouseRef);

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
