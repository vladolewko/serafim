<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NovaPostService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NOVA_POST_API_KEY');
    }

    public function searchSettlement($search)
    {
        $response = $this->makeRequest('AddressGeneral', 'searchSettlements', [
            'CityName' => $search,
            'Limit' => '500',
            'Page' => '1'
        ]);

        return $response['data'][0]['Addresses'] ?? [];
    }

    public function getWarehouses($settlementRef)
    {
        $response = $this->makeRequest('AddressGeneral', 'getWarehouses', [
            'SettlementRef' => $settlementRef,
        ]);

        return $response['data'] ?? [];
    }

    public function createCounterparty($data)
    {
        $response = $this->makeRequest('CounterpartyGeneral', 'save', [
            'FirstName' => $data['name'],
            'MiddleName' => '',
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
            'Email' => $data['email'],
            'CounterpartyType' => 'PrivatePerson',
            'CounterpartyProperty' => 'Recipient',
        ]);

        if (!isset($response['data'][0])) {
            throw new \Exception('Помилка створення контрагента');
        }

        $counterpartyRef = $response['data'][0]['Ref'];
        $contactPersonRef = $response['data'][0]['ContactPerson']['data'][0]['Ref'] ?? null;

        if (!$contactPersonRef) {
            $contactResponse = $this->createContactPerson($counterpartyRef, $data);
            $contactPersonRef = $contactResponse['data'][0]['Ref'] ?? null;
        }

        return [
                'Ref' => $counterpartyRef,
                'ContactPersonRef' => $contactPersonRef,
            ] + $response['data'][0];
    }

    public function createContactPerson($counterpartyRef, $data)
    {
        $response = $this->makeRequest('ContactPersonGeneral', 'save', [
            'CounterpartyRef' => $counterpartyRef,
            'FirstName' => $data['name'],
            'MiddleName' => '',
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
        ]);

        return $response;
    }

    public function createTTN($data, $cart, $payment)
    {
        $this->validateSenderConfiguration();

        $product = $cart['product'];
        $quantity = $cart['quantity'] ?? 1;

        $contactRecipient = $data['contact_person_ref'] ?? $this->getContactPerson($data['counterparty_ref']);
        if (!$contactRecipient) {
            throw new \Exception('Не вдалося отримати контактну особу отримувача');
        }

        $serviceType = $this->determineServiceType($data['warehouse']);
        $isDropOff = $serviceType === 'WarehouseDoors';

        if ($isDropOff && $quantity > 1) {
            throw new \Exception('Для доставки до поштоматів можна відправити лише 1 товар. Оберіть відділення Нової Пошти або зменште кількість товарів.');
        }

        $productData = is_object($product) ? $product : (object)$product;
        $totalWeight = $productData->weight * $quantity;
        $totalVolume = $this->calculateTotalVolume($productData->dimension ?? '', $quantity);

        $ttnData = [
            'PayerType' => 'Sender',
            'PaymentMethod' => 'Cash',
            'DateTime' => now()->addDay()->format('d.m.Y'),
            'CargoType' => 'Cargo',
            'VolumeGeneral' => (string)$totalVolume,
            'Weight' => (string)$totalWeight,
            'ServiceType' => $serviceType,
            'SeatsAmount' => $isDropOff ? '1' : (string)$quantity,
            'Description' => $this->generateDescription($productData, $quantity),
            'CitySender' => env('NOVA_POST_CITY_SENDER'),
            'Sender' => env('NOVA_POST_SENDER_REF'),
            'SenderAddress' => env('NOVA_POST_SENDER_ADDRESS'),
            'ContactSender' => env('NOVA_POST_CONTACT_SENDER'),
            'SendersPhone' => env('NOVA_POST_SENDER_PHONE'),
            'CityRecipient' => $data['settlement'],
            'Recipient' => $data['counterparty_ref'],
            'RecipientAddress' => $data['warehouse'],
            'ContactRecipient' => $contactRecipient,
            'RecipientsPhone' => $data['phone'],
        ];

        if ($payment === 'cash') {
            $cartTotal = is_array($cart) ? $cart['total'] : $cart->total;
            $ttnData['BackwardDeliveryData'] = [
                [
                    'PayerType' => 'Recipient',
                    'CargoType' => 'Money',
                    'RedeliveryString' => $cartTotal,
                ],
            ];
        }

        $response = $this->makeRequest('InternetDocumentGeneral', 'save', $ttnData);

        if (!isset($response['data'][0])) {
            $errors = $response['errors'] ?? ['Невідома помилка створення ТТН'];
            throw new \Exception('Помилка створення ТТН: ' . implode(', ', $errors));
        }

        return $response['data'][0];
    }

    public function getServiceCosts(string $recipientCityRef, float $weight, float $total,  int $quantity = 1)
    {
        $request = $this->makeRequest('InternetDocument', 'getDocumentPrice', [
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
        ])['data'][0];

        return $request['Cost'] + $request['CostRedelivery'];
    }

    public function setupSender($data)
    {
        $senderRef = $this->createOrGetSender($data);
        if (!$senderRef) {
            throw new \Exception('Не вдалося створити/отримати відправника');
        }

        // Оновлюємо всі дані в .env
        $cityRef = $this->setCitySender($data['city']);
        if (!$cityRef) {
            throw new \Exception('Не вдалося знайти місто відправника');
        }

        $senderSettlement = $this->searchSettlement($data['city']);
        if (empty($senderSettlement)) {
            throw new \Exception('Не вдалося знайти населений пункт');
        }

        $senderAddress = $this->getSenderAddress($senderSettlement[0]['Ref']);
        if (!$senderAddress) {
            throw new \Exception('Не вдалося отримати адресу відправника');
        }

        $contactSender = $this->getContactPerson($senderRef);
        if (!$contactSender) {
            throw new \Exception('Не вдалося отримати контактну особу відправника');
        }

        // Оновлюємо всі дані в .env
        $this->updateEnvFile('NOVA_POST_CITY_SENDER', $cityRef);
        $this->updateEnvFile('NOVA_POST_SENDER_REF', $senderRef);
        $this->updateEnvFile('NOVA_POST_SENDER_ADDRESS', $senderAddress);
        $this->updateEnvFile('NOVA_POST_CONTACT_SENDER', $contactSender);
        $this->updateEnvFile('NOVA_POST_SENDER_PHONE', $data['phone']);

        return true;
    }

    public function checkSenderSetup()
    {
        $requiredVars = [
            'NOVA_POST_CITY_SENDER' => 'Місто відправника',
            'NOVA_POST_SENDER_REF' => 'Відправник',
            'NOVA_POST_SENDER_ADDRESS' => 'Адреса(відділення) відправника',
            'NOVA_POST_CONTACT_SENDER' => 'Контактна особа відправника',
            'NOVA_POST_SENDER_PHONE' => 'Телефон відправника'
        ];

        $missing = [];
        $existing = [];

        foreach ($requiredVars as $var => $name) {
            if (empty(env($var))) {
                $missing[] = $name;
            } else {
                $existing[$name] = env($var);
            }
        }

        return [
            'is_configured' => empty($missing),
            'missing' => $missing,
            'existing' => $existing
        ];
    }

    public function testApiKey()
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

    private function validateSenderConfiguration()
    {
        $requiredEnvVars = [
            'NOVA_POST_CITY_SENDER',
            'NOVA_POST_SENDER_REF',
            'NOVA_POST_SENDER_ADDRESS',
            'NOVA_POST_CONTACT_SENDER',
            'NOVA_POST_SENDER_PHONE'
        ];

        foreach ($requiredEnvVars as $var) {
            if (empty(env($var))) {
                throw new \Exception("Не налаштована змінна оточення: {$var}. Спочатку налаштуйте відправника.");
            }
        }
    }

    private function calculateTotalVolume($dimensions, $quantity)
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

    private function generateDescription($product, $quantity)
    {
        $productName = $product->name ?? 'Товар';

        if ($quantity > 1) {
            return "Замовлення з інтернет-магазину: {$productName} x{$quantity}";
        }

        return "Замовлення з інтернет-магазину: {$productName}";
    }

    private function determineServiceType($warehouseRef)
    {
        try {
            $warehouseInfo = $this->getWarehouseInfo($warehouseRef);

            if (!$warehouseInfo) {
                return 'WarehouseWarehouse';
            }

            if (isset($warehouseInfo['TypeOfWarehouse']) &&
                $warehouseInfo['TypeOfWarehouse'] === 'PostBox') {
                return 'WarehouseDoors';
            }

            return 'WarehouseWarehouse';
        } catch (\Exception $e) {
            return 'WarehouseWarehouse';
        }
    }

    private function getWarehouseInfo($warehouseRef)
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

    private function setCitySender($cityName)
    {
        $response = $this->makeRequest('Address', 'getCities', [
            'FindByString' => $cityName,
        ]);

        return $response['data'][0]['Ref'] ?? null;
    }

    private function createOrGetSender($data)
    {
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
            'Email' => '',
            'CounterpartyType' => 'PrivatePerson',
            'CounterpartyProperty' => 'Sender'
        ]);

        $senderRef = $response['data'][0]['Ref'] ?? null;

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

    private function getSenderAddress($cityRef)
    {
        $response = $this->makeRequest('AddressGeneral', 'getWarehouses', [
            'SettlementRef' => $cityRef,
        ]);

        if (isset($response['data'][0]['Ref'])) {
            return $response['data'][0]['Ref'];
        }

        throw new \Exception('Не вдалося знайти відділення для створення адреси відправника');
    }

    private function getContactPerson($counterpartyRef)
    {
        $response = $this->makeRequest('CounterpartyGeneral', 'getCounterpartyContactPersons', [
            'Ref' => $counterpartyRef,
        ]);

        return $response['data'][0]['Ref'] ?? null;
    }

    private function makeRequest($modelName, $calledMethod, $methodProperties = [])
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

            if (isset($data['errors']) && !empty($data['errors'])) {
                throw new \Exception('API помилка: ' . implode(', ', $data['errors']));
            }

            return $data;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception('Помилка з\'єднання з API Нової Пошти. Перевірте інтернет-з\'єднання.');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            throw new \Exception('Помилка запиту до API Нової Пошти.');
        }
    }

    private function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        if (strpos($envContent, $key) !== false) {
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
        } else {
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $envContent);
    }
}
