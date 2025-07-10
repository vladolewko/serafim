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

            $deliveryServiceId = null;
            $paymentData = [];
            if ($order->payment_type == 'cash') {
                $deliveryServiceId = 1; // ID для наложеного платежу
            } elseif ($order->payment_type == 'card') {
                $deliveryServiceId = 2; // ID для оплати карткою

                // Додаємо дані про оплату тільки якщо замовлення оплачено
                if ($order->status == 'paid' || $order->payment_status == 'paid') {
                    $paymentData[] = [
                        "payment_method_id" => 1,
                        "payment_method" => "WayForPay",
                        "amount" => $order->product_total,
                        "description" => "Повна оплата замовлення",
                        "payment_date" => now()->format('Y-m-d H:i:s'),
                        "status" => "paid",
                    ];
                }
            }

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
                'source_id' => 1,
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
                return null;
            }

            $responseData = $response->json();

            // Повертаємо перший знайдений товар або null
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
     * Створити товар у keyCRM з перевіркою існування
     */
    public function createProduct(Product $product)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            $sku = 'PROD-' . $product->id;

            // Перевіряємо чи існує товар у keyCRM
            $existingProduct = $this->findProductBySku($sku);

            if ($existingProduct) {
                // Товар існує, оновлюємо keycrm_id та викликаємо updateProduct
                $product->update(['keycrm_id' => $existingProduct['id']]);
                return $this->updateProduct($product);
            }

            $productData = [
                'name' => $product->name,
                'description' => $product->description ?? '',
                'pictures' => [str_replace('http://110.172.148.57:8000', 'https://serafym.info', $product->getFirstMediaUrl('product_images'))],
                'price' => (float)$product->price,
                'currency_code' => 'UAH',
                'weight' => (float)$product->weight,
                'height' => (float)$product->height,
                'length' => (float)$product->length,
                'width' => (float)$product->width,
                'sku' => $sku,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->post($this->apiUrl . '/v1/products', $productData);

            if (!$response->successful()) {
                $errorMessage = $response->json()['message'] ?? 'Unknown API error';
                throw new \Exception($errorMessage);
            }

            $responseData = $response->json();

            // Зберігаємо ID товару з keyCRM в базі даних
            $product->update(['keycrm_id' => $responseData['id'] ?? null]);

            return $responseData;

        } catch (\Exception $exception) {
            Log::error('KeyCRMService createProduct error: ' . $exception->getMessage(), [
                'product_id' => $product->id ?? null,
            ]);
            return null;
        }
    }

    /**
     * Оновити товар у keyCRM з перевіркою існування
     */
    public function updateProduct(Product $product)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            $sku = 'PROD-' . $product->id;

            // Якщо немає keycrm_id, спробуємо знайти товар за SKU
            if (!$product->keycrm_id) {
                $existingProduct = $this->findProductBySku($sku);

                if ($existingProduct) {
                    // Знайшли товар, оновлюємо keycrm_id
                    $product->update(['keycrm_id' => $existingProduct['id']]);
                } else {
                    // Товар не знайдено, створюємо новий
                    return $this->createProduct($product);
                }
            }

            $productData = [
                'name' => $product->name,
                'description' => $product->description ?? '',
                'pictures' => [str_replace('http://110.172.148.57:8000', 'https://serafym.info', $product->getFirstMediaUrl('product_images'))],
                'price' => (float)$product->price,
                'currency_code' => 'UAH',
                'weight' => (float)$product->weight,
                'height' => (float)$product->height,
                'length' => (float)$product->length,
                'width' => (float)$product->width,
                'sku' => $sku,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->put($this->apiUrl . '/v1/products/' . $product->keycrm_id, $productData);

            if (!$response->successful()) {
                $errorMessage = $response->json()['message'] ?? 'Unknown API error';
                throw new \Exception($errorMessage);
            }

            return $response->json();

        } catch (\Exception $exception) {
            Log::error('KeyCRMService updateProduct error: ' . $exception->getMessage(), [
                'product_id' => $product->id ?? null,
                'keycrm_id' => $product->keycrm_id ?? null,
                'response_body' => isset($response) ? $response->body() : null
            ]);
            return null;
        }
    }


}
