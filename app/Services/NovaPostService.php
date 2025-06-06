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
            'MiddleName' => $data['middlename'],
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
            'Email' => 'test@example.com',
            'CounterpartyType' => 'PrivatePerson',
            'CounterpartyProperty' => 'Recipient',
        ]);

        if (!isset($response['data'][0])) {
            throw new \Exception('Помилка створення контрагента');
        }

        $this->createContactPerson($response['data'][0]['Ref'], $data);

        return $response['data'][0];
    }

    public function createContactPerson($counterpartyRef, $data)
    {
        return $this->makeRequest('ContactPersonGeneral', 'save', [
            'CounterpartyRef' => $counterpartyRef,
            'FirstName' => $data['name'],
            'MiddleName' => $data['middlename'],
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
        ]);
    }

    public function createTTN($data)
    {
        try {
            Log::info('Creating TTN with data', $data);
            
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

            $contactRecipient = $this->getContactPerson($data['counterparty_ref']);
            
            if (!$contactRecipient) {
                throw new \Exception('Не вдалося отримати контактну особу отримувача');
            }

            $ttnData = [
                'PayerType' => 'Sender',
                'PaymentMethod' => 'Cash',
                'DateTime' => now()->addDay()->format('d.m.Y'),
                'CargoType' => 'Cargo',
                'VolumeGeneral' => '0.1',
                'Weight' => '0.5',
                'ServiceType' => 'DoorsWarehouse',
                'SeatsAmount' => '1',
                'Description' => 'Замовлення з інтернет-магазину',
                'Cost' => '1500',
                'CitySender' => env('NOVA_POST_CITY_SENDER'),
                'Sender' => env('NOVA_POST_SENDER_REF'),
                'SenderAddress' => env('NOVA_POST_SENDER_ADDRESS'),
                'ContactSender' => env('NOVA_POST_CONTACT_SENDER'),
                'SendersPhone' => env('NOVA_POST_SENDER_PHONE'),
                'CityRecipient' => $data['settlement'],
                'Recipient' => $data['counterparty_ref'],
                'RecipientAddress' => $data['warehouse'],
                'ContactRecipient' => $contactRecipient,
                'RecipientsPhone' => $data['phone']
            ];

            Log::info('TTN request data', $ttnData);

            $response = $this->makeRequest('InternetDocumentGeneral', 'save', $ttnData);

            Log::info('TTN API response', $response);

            if (!isset($response['data'][0])) {
                $errors = $response['errors'] ?? ['Невідома помилка створення ТТН'];
                Log::error('TTN creation failed', ['errors' => $errors]);
                throw new \Exception('Помилка створення ТТН: ' . implode(', ', $errors));
            }

            Log::info('TTN created successfully', $response['data'][0]);
            return $response['data'][0];
            
        } catch (\Exception $e) {
            Log::error('TTN creation exception', [
                'message' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function setupSender($data)
    {
        try {
            // Встановлюємо місто відправника
            $cityRef = $this->setCitySender($data['city']);
            if (!$cityRef) {
                throw new \Exception('Не вдалося знайти місто відправника');
            }

            // Створюємо/отримуємо відправника  
            $senderRef = $this->createOrGetSender($data);
            if (!$senderRef) {
                throw new \Exception('Не вдалося створити/отримати відправника');
            }

            // Отримуємо адресу відправника
            $senderAddress = $this->getSenderAddress($senderRef, $cityRef);
            if (!$senderAddress) {
                throw new \Exception('Не вдалося отримати адресу відправника');
            }

            // Отримуємо контакт відправника
            $contactSender = $this->getContactPerson($senderRef);
            if (!$contactSender) {
                throw new \Exception('Не вдалося отримати контактну особу відправника');
            }

            // Зберігаємо
            $this->updateEnvFile('NOVA_POST_CITY_SENDER', $cityRef);
            $this->updateEnvFile('NOVA_POST_SENDER_REF', $senderRef);
            $this->updateEnvFile('NOVA_POST_SENDER_ADDRESS', $senderAddress);
            $this->updateEnvFile('NOVA_POST_CONTACT_SENDER', $contactSender);
            $this->updateEnvFile('NOVA_POST_SENDER_PHONE', $data['phone']);

            return true;
        } catch (\Exception $e) {
            Log::error('Помилка налаштування відправника: ' . $e->getMessage());
            return false;
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
        // Спочатку шукаємо існуючого
        $response = $this->makeRequest('Counterparty', 'getCounterparties', [
            'CounterpartyProperty' => 'Sender'
        ]);

        if (isset($response['data'][0])) {
            return $response['data'][0]['Ref'];
        }

        // Створюємо нового відправника
        $response = $this->makeRequest('Counterparty', 'save', [
            'FirstName' => $data['name'],
            'LastName' => $data['surname'],
            'Phone' => $data['phone'],
            'Email' => '',
            'CounterpartyType' => 'PrivatePerson',
            'CounterpartyProperty' => 'Sender'
        ]);

        $senderRef = $response['data'][0]['Ref'] ?? null;

        if ($senderRef) {
            // Після створення відправника, створюємо контактну особу
            $this->makeRequest('ContactPersonGeneral', 'save', [
                'CounterpartyRef' => $senderRef,
                'FirstName' => $data['name'],
                'MiddleName' => '', // Якщо немає по батькові
                'LastName' => $data['surname'],
                'Phone' => $data['phone'],
            ]);
        }

        return $senderRef;
    }

    // ВИПРАВЛЕНО: додано параметр cityRef та створення адреси якщо потрібно
    private function getSenderAddress($senderRef, $cityRef)
    {
        $response = $this->makeRequest('CounterpartyGeneral', 'getCounterpartyAddresses', [
            'Ref' => $senderRef,
            'CounterpartyProperty' => 'Sender',
        ]);

        // Якщо адреса існує, повертаємо її
        if (isset($response['data'][0]['Ref'])) {
            return $response['data'][0]['Ref'];
        }

        // Якщо адреси немає, спочатку знаходимо вулицю в місті
        $streetRef = $this->getStreetRef($cityRef);
        
        if (!$streetRef) {
            throw new \Exception('Не вдалося знайти вулицю для створення адреси відправника');
        }

        // Створюємо адресу з правильним StreetRef
        $addressResponse = $this->makeRequest('Address', 'save', [
            'CounterpartyRef' => $senderRef,
            'StreetRef' => $streetRef,
            'BuildingNumber' => '1',
            'Flat' => '',
            'Note' => 'Адреса відправника'
        ]);

        return $addressResponse['data'][0]['Ref'] ?? null;
    }

    private function getStreetRef($cityRef)
    {
        $response = $this->makeRequest('Address', 'getStreet', [
            'CityRef' => $cityRef,
            'FindByString' => 'Центральна'
        ]);

        if (empty($response['data'])) {
            $commonStreets = ['Головна', 'Перша', 'Київська', 'Шевченка', 'Незалежності'];
            
            foreach ($commonStreets as $streetName) {
                $response = $this->makeRequest('Address', 'getStreet', [
                    'CityRef' => $cityRef,
                    'FindByString' => $streetName
                ]);
                
                if (!empty($response['data'])) {
                    break;
                }
            }
        }

        if (empty($response['data'])) {
            $response = $this->makeRequest('Address', 'getStreet', [
                'CityRef' => $cityRef,
                'Page' => '1'
            ]);
        }

        return $response['data'][0]['Ref'] ?? null;
    }

    private function getContactPerson($counterpartyRef)
    {
        $response = $this->makeRequest('CounterpartyGeneral', 'getCounterpartyContactPersons', [
            'Ref' => $counterpartyRef,
        ]);

        return $response['data'][0]['Ref'] ?? null;
    }

    public function checkSenderSetup()
    {
        $requiredVars = [
            'NOVA_POST_CITY_SENDER' => 'Місто відправника',
            'NOVA_POST_SENDER_REF' => 'Відправник',
            'NOVA_POST_SENDER_ADDRESS' => 'Адреса відправника',
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

    public function trackTTN($ttnNumber, $phone = null)
    {
        $response = $this->makeRequest('TrackingDocument', 'getStatusDocuments', [
            'Documents' => [
                [
                    'DocumentNumber' => $ttnNumber,
                    'Phone' => $phone
                ]
            ]
        ]);

        return $response['data'] ?? [];
    }

    private function makeRequest($modelName, $calledMethod, $methodProperties = [])
    {
        try {
            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->withOptions([
                    'verify' => false, 
                ])
                ->post('https://api.novaposhta.ua/v2.0/json/', [
                    'apiKey' => $this->apiKey,
                    'modelName' => $modelName,
                    'calledMethod' => $calledMethod,
                    'methodProperties' => $methodProperties
                ]);

            if (!$response->successful()) {
                Log::error('Nova Post API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'model' => $modelName,
                    'method' => $calledMethod
                ]);
                throw new \Exception('API запит не вдався. HTTP код: ' . $response->status());
            }

            $data = $response->json();
            
            // Перевіряємо чи є помилки в відповіді API
            if (isset($data['errors']) && !empty($data['errors'])) {
                Log::error('Nova Post API response errors', [
                    'errors' => $data['errors'],
                    'model' => $modelName,
                    'method' => $calledMethod
                ]);
                throw new \Exception('API помилка: ' . implode(', ', $data['errors']));
            }

            return $data;
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Nova Post connection error', [
                'message' => $e->getMessage(),
                'model' => $modelName,
                'method' => $calledMethod
            ]);
            throw new \Exception('Помилка з\'єднання з API Нової Пошти. Перевірте інтернет-з\'єднання.');
            
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Nova Post request error', [
                'message' => $e->getMessage(),
                'model' => $modelName,
                'method' => $calledMethod
            ]);
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