<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KeyCrmService
{
    private $apiKey;
    private $apiUrl;
    private $novaPostService;
    private ProductService $productService;

    protected $deliveryServices = [];
    protected $paymentMethods = [];

    public function getDeliveryServices()
    {
        if (empty($this->deliveryServices)) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->apiUrl . '/v1/order/delivery-service');

            if ($response->successful()) {
                $this->deliveryServices = collect($response->json()['data'] ?? [])
                    ->keyBy('name');
            }
        }

        return $this->deliveryServices;
    }

    public function getPaymentMethods()
    {
        if (empty($this->paymentMethods)) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->apiUrl . '/v1/order/payment-method');

            if ($response->successful()) {
                $this->paymentMethods = collect($response->json()['data'] ?? [])
                    ->keyBy('name');
            }
        }

        return $this->paymentMethods;
    }

    public function __construct(NovaPostService $novaPostService, ProductService $productService)
    {
        $this->apiKey = env('KEY_CRM_API_KEY');
        $this->apiUrl = env('KEY_CRM_API_URL');
        $this->novaPostService = $novaPostService;
        $this->productService = $productService;
    }

    public function sendOrderToCrm(Order $order)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            $warehouseData = $this->novaPostService->getWarehouseInfo($order->warehouse_ref);

            // Отримуємо актуальні дані з CRM
            $deliveryServices = $this->getDeliveryServices();
            $paymentMethods = $this->getPaymentMethods();

            // Знаходимо ID служби доставки Нова Пошта
            $novaPoshtaService = $deliveryServices->first(function ($service) {
                return stripos($service['source_name'], 'novaposhta') !== false;
            });

            $deliveryServiceId = $novaPoshtaService['id'] ?? null;

            $paymentData = [];
            if ($order->payment_type == 'card' && ($order->status == 'paid' || $order->payment_status == 'paid')) {
                // Знаходимо ID для WayForPay
                $wayForPayMethod = $paymentMethods->first(function ($method) {
                    return stripos($method['name'], 'WayForPay') !== false;
                });

                if ($wayForPayMethod) {
                    $paymentData[] = [
                        "payment_method_id" => $wayForPayMethod['id'],
                        "payment_method" => $wayForPayMethod['name'],
                        "amount" => $order->product_total,
                        "description" => "Повна оплата замовлення",
                        "payment_date" => now()->format('Y-m-d H:i:s'),
                        "status" => "paid",
                    ];
                }
            }

            Log::info('Source ID for Serafim: ' . $this->getSourceIdByAlias('serafyminfo'));

            $customerName = trim($order->customer_name . ' ' . $order->customer_surname);

            $products = [];
            $product = $this->productService->getById($order->cart_data['productId']);

            if ($product) {
                $productSku = "PROD-" .  $order->cart_data['productId'];

                if ($productSku) {

                    $products[] = [
                        "sku" => $productSku,
                        "price" => (float)$product->price,
                        "purchased_price" => (float)$product->price,
                        "discount_percent" => 0,
                        "discount_amount" => 0,
                        "quantity" => $order->cart_data['quantity'],
                        "unit_type" => "шт",
                        "name" => $product->name,
                    ];
                }
            }
            $warehouseNumber = $warehouseData['Number'];
            $warehouseAddress = $warehouseData['ShortAddress'];
            $warehouseName = "Відділення №{$warehouseNumber}";

            $orderData = [
                'source_id' => $this->getSourceIdByAlias('serafyminfo'),
                'buyer' => [
                    'full_name' => $customerName,
                    'phone' => $order->customer_phone,
                    'email' => $order->customer_email ?? '',
                ],
                'shipping' => [
                    'delivery_service_id' => $deliveryServiceId,
                    'shipping_service' => 'Нова Пошта',
                    'shipping_address_city' => $warehouseData['CityDescription'],
                    'shipping_address_country' => 'Ukraine',
                    'shipping_address_region' => $warehouseData['SettlementAreaDescription'],
                    'shipping_address_zip' => $warehouseData['PostalCodeUA'],
                    'shipping_secondary_line' => $warehouseAddress,
                    'shipping_receive_point' => $warehouseName,
                    'warehouse_ref' => $warehouseData['Ref'],
                    'recipient_full_name' => $customerName,
                    'recipient_phone' => $order->customer_phone,
                ],
                'total_price' => (float)$order->product_total,
                'products' => $products,
            ];

            if (!empty($paymentData)) {
                $orderData['payments'] = $paymentData;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->post($this->apiUrl . '/v1/order', $orderData);

            if (!$response->successful()) {
                $errorMessage = $response->json()['message'] ?? 'Unknown API error';
                throw new \Exception($errorMessage);
            }

            $orderResponse = $response->json();

            $keyCrmOrderId = $orderResponse['id'] ?? null;

            if ($keyCrmOrderId) {
                $order->update(['keycrm_order_id' => $keyCrmOrderId]);

            }

            return $orderResponse;

        } catch (\Exception $exception) {
            Log::error('KeyCRMService error: ' . $exception->getMessage(), [
                'order_id' => $order->id ?? null,
            ]);
            return null;
        }
    }


    /**
     * Перевірити чи існує товар у keyCRM за SKU
     */
    public function findProductBySku($sku)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->get($this->apiUrl . '/v1/products', [
                    'filter[sku]' => $sku
                ]);

            if (!$response->successful()) {
                Log::warning('KeyCRMService findProductBySku failed', [
                    'sku' => $sku,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }

            $responseData = $response->json();
            return isset($responseData['data']) && count($responseData['data']) > 0
                ? $responseData['data'][0]
                : null;

        } catch (\Exception $exception) {
            Log::error('KeyCRMService findProductBySku error: ' . $exception->getMessage(), [
                'sku' => $sku
            ]);
            return null;
        }
    }

    /**
     * Перевірити чи існує товар у keyCRM за ID
     */
    public function findProductById($keycrmId)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->get($this->apiUrl . '/v1/products/' . $keycrmId);

            if ($response->status() === 404) {
                return null; // Товар не існує
            }

            if (!$response->successful()) {
                Log::warning('KeyCRMService findProductById failed', [
                    'keycrm_id' => $keycrmId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }

            return $response->json();

        } catch (\Exception $exception) {
            Log::error('KeyCRMService findProductById error: ' . $exception->getMessage(), [
                'keycrm_id' => $keycrmId
            ]);
            return null;
        }
    }

    /**
     * Генерувати унікальний SKU для товару (тільки якщо його немає)
     */
    private function generateSku(Product $product)
    {
        if ($product->sku) {
            return $product->sku; // Використовуємо існуючий SKU
        }

        return 'PROD-' . $product->id . '-' . date('YmdHis') . rand(100, 999);
    }

    /**
     * Підготувати дані товару для API
     */
    private function prepareProductData(Product $product)
    {
        return [
            'name' => $product->name,
            'description' => $product->description ?? '',
            'pictures' => [str_replace('http://110.172.148.57:8000', 'https://serafym.info', $product->getFirstMediaUrl('product_images'))],
            'price' => (float)$product->price,
            'currency_code' => 'UAH',
            'weight' => (float)$product->weight,
            'height' => (float)$product->height,
            'length' => (float)$product->length,
            'width' => (float)$product->width,
            'sku' => $product->sku,
        ];
    }
    /**
     * Знайти товар у keyCRM за назвою
     */
    public function findProductByName($name)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->get($this->apiUrl . '/v1/products', [
                    'filter[name]' => $name
                ]);

            if (!$response->successful()) {
                Log::warning('KeyCRMService findProductByName failed', [
                    'name' => $name,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }

            $responseData = $response->json();
            return isset($responseData['data']) && count($responseData['data']) > 0
                ? $responseData['data'][0]
                : null;

        } catch (\Exception $exception) {
            Log::error('KeyCRMService findProductByName error: ' . $exception->getMessage(), [
                'name' => $name
            ]);
            return null;
        }
    }

    /**
     * Синхронізувати товар з KeyCRM (створити або оновити)
     */
    public function syncProduct(Product $product)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            // Генеруємо SKU тільки якщо його немає
            if (!$product->sku) {
                $sku = $this->generateSku($product);
                $product->update(['sku' => $sku]);
            }

            // Спробуємо знайти існуючий товар
            $existingProduct = null;

            // 1. Спочатку шукаємо за keycrm_id якщо він є
            if ($product->keycrm_id) {
                $existingProduct = $this->findProductById($product->keycrm_id);

                // Якщо товар не знайдено за ID, очищуємо keycrm_id
                if (!$existingProduct) {
                    Log::info('Product not found by keycrm_id, clearing it', [
                        'product_id' => $product->id,
                        'keycrm_id' => $product->keycrm_id
                    ]);
                    $product->update(['keycrm_id' => null]);
                }
            }

            // 2. Якщо не знайшли за ID, шукаємо за SKU
            if (!$existingProduct) {
                $existingProduct = $this->findProductBySku($product->sku);

                // Якщо знайшли за SKU, оновлюємо keycrm_id
                if ($existingProduct) {
                    $product->update(['keycrm_id' => $existingProduct['id']]);
                }
            }

            // 3. Якщо не знайшли за SKU, шукаємо за назвою
            if (!$existingProduct) {
                $existingProduct = $this->findProductByName($product->name);

                // Якщо знайшли за назвою, оновлюємо keycrm_id та SKU
                if ($existingProduct) {
                    $product->update([
                        'keycrm_id' => $existingProduct['id']
                    ]);

                    Log::info('Found existing product by name, linked to local product', [
                        'product_id' => $product->id,
                        'keycrm_id' => $existingProduct['id'],
                        'name' => $product->name
                    ]);
                }
            }

            // Тепер або створюємо, або оновлюємо
            if ($existingProduct) {
                return $this->updateExistingProduct($product);
            } else {
                return $this->createNewProduct($product);
            }

        } catch (\Exception $exception) {
            Log::error('KeyCRMService syncProduct error: ' . $exception->getMessage(), [
                'product_id' => $product->id ?? null,
            ]);
            return null;
        }
    }

    /**
     * Оновити існуючий товар у KeyCRM
     */
    private function updateExistingProduct(Product $product)
    {
        try {
            if (!$product->keycrm_id) {
                throw new \Exception('Cannot update product without keycrm_id');
            }

            $productData = $this->prepareProductData($product);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->put($this->apiUrl . '/v1/products/' . $product->keycrm_id, $productData);

            if (!$response->successful()) {
                $errorMessage = $response->json()['message'] ?? 'Unknown API error';

                // Якщо товар не знайдено, спробуємо створити новий
                if ($response->status() === 404) {
                    Log::warning('Product not found in KeyCRM, creating new one', [
                        'product_id' => $product->id,
                        'keycrm_id' => $product->keycrm_id
                    ]);
                    $product->update(['keycrm_id' => null]);
                    return $this->createNewProduct($product);
                }

                Log::error('KeyCRMService updateExistingProduct failed', [
                    'product_id' => $product->id,
                    'keycrm_id' => $product->keycrm_id,
                    'status' => $response->status(),
                    'error' => $errorMessage,
                    'response' => $response->body()
                ]);
                throw new \Exception($errorMessage);
            }

            Log::info('Product updated in KeyCRM', [
                'product_id' => $product->id,
                'keycrm_id' => $product->keycrm_id
            ]);

            return $response->json();

        } catch (\Exception $exception) {
            Log::error('KeyCRMService updateExistingProduct error: ' . $exception->getMessage(), [
                'product_id' => $product->id ?? null,
                'keycrm_id' => $product->keycrm_id ?? null,
            ]);
            throw $exception;
        }
    }

    /**
     * Створити новий товар у KeyCRM з унікальною назвою
     */
    private function createNewProduct(Product $product)
    {
        try {
            $productData = $this->prepareProductData($product);
            $originalName = $productData['name'];
            $attempt = 0;

            // Якщо назва вже існує, додаємо суфікс
            while ($attempt < 10) { // Максимум 10 спроб
                if ($attempt > 0) {
                    $productData['name'] = $originalName . ' (' . $attempt . ')';

                    // Оновлюємо назву в локальній базі даних
                    $product->update(['name' => $productData['name']]);
                }

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(30)
                    ->post($this->apiUrl . '/v1/products', $productData);

                if ($response->successful()) {
                    $responseData = $response->json();

                    // Зберігаємо ID товару з keyCRM в базі даних
                    $product->update(['keycrm_id' => $responseData['id'] ?? null]);

                    Log::info('Product created in KeyCRM', [
                        'product_id' => $product->id,
                        'keycrm_id' => $responseData['id'] ?? null,
                        'final_name' => $productData['name'],
                        'attempts' => $attempt + 1
                    ]);

                    return $responseData;
                }

                // Перевіряємо чи помилка через дублікат назви
                $responseJson = $response->json();
                if ($response->status() === 422 &&
                    isset($responseJson['errors']['name']) &&
                    str_contains(implode(' ', $responseJson['errors']['name']), 'has already been taken')) {

                    $attempt++;
                    continue; // Пробуємо з новою назвою
                }

                // Інша помилка - кидаємо виняток
                $errorMessage = $responseJson['message'] ?? 'Unknown API error';
                Log::error('KeyCRMService createNewProduct failed', [
                    'product_id' => $product->id,
                    'status' => $response->status(),
                    'error' => $errorMessage,
                    'response' => $response->body()
                ]);
                throw new \Exception($errorMessage);
            }

            throw new \Exception('Could not create product after 10 attempts - name conflicts');

        } catch (\Exception $exception) {
            Log::error('KeyCRMService createNewProduct error: ' . $exception->getMessage(), [
                'product_id' => $product->id ?? null,
            ]);
            throw $exception;
        }
    }

    /**
     * Перевірити та відновити зв'язки для всіх товарів без keycrm_id
     */
    public function repairBrokenLinks()
    {
        try {
            $productsWithoutKeycrmId = Product::whereNull('keycrm_id')
                ->whereNotNull('sku')
                ->get();

            $repairedCount = 0;

            foreach ($productsWithoutKeycrmId as $product) {
                $existingProduct = null;

                // Спочатку шукаємо за SKU
                $existingProduct = $this->findProductBySku($product->sku);

                // Якщо не знайшли за SKU, шукаємо за назвою
                if (!$existingProduct) {
                    $existingProduct = $this->findProductByName($product->name);
                }

                if ($existingProduct) {
                    $product->update(['keycrm_id' => $existingProduct['id']]);
                    $repairedCount++;
                    Log::info('Repaired product link', [
                        'product_id' => $product->id,
                        'keycrm_id' => $existingProduct['id'],
                        'matched_by' => $this->findProductBySku($product->sku) ? 'sku' : 'name'
                    ]);
                }
            }

            return [
                'checked' => $productsWithoutKeycrmId->count(),
                'repaired' => $repairedCount
            ];

        } catch (\Exception $exception) {
            Log::error('KeyCRMService repairBrokenLinks error: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Отримати ID джерела по його alias
     */
    public function getSourceIdByAlias(string $alias): ?int
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->get($this->apiUrl . '/v1/order/source');

            if (!$response->successful()) {
                Log::error('KeyCRM API error: ' . $response->body());
                return null;
            }

            $data = $response->json();
            $sources = $data['data'] ?? $data;
            Log::info('KeyCRM API response: ' . $response->body());

            // Шукаємо джерело по alias
            foreach ($sources as $source) {
                if (isset($source['alias']) && $source['alias'] === $alias) {
                    Log::info("Found source with alias '{$alias}': " . json_encode($source));
                    return $source['id'] ?? null;
                }
            }

            Log::warning("Source with alias '{$alias}' not found in KeyCRM");
            return null;
        } catch (\Exception $e) {
            Log::error("Failed to get source ID for alias '{$alias}': " . $e->getMessage());
            return null;
        }
    }


}
