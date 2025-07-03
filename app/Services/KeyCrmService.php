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

    public function __construct(NovaPostService $novaPostService)
    {
        $this->apiKey = env('KEY_CRM_API_KEY');
        $this->apiUrl = env('KEY_CRM_API_URL');
        $this->novaPostService = $novaPostService;
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
                $paymentData[] = [
                    "payment_method_id" => 1,
                    "payment_method" => "WayForPay",
                    "amount" => $order->total_amount,
                    "description" => "Повна оплата замовлення",
                    "payment_date" => now()->format('Y-m-d H:i:s'),
                    "status" => "paid",
                ];
            }

            $customerName = trim($order->customer_name . ' ' . $order->customer_surname);

            $products = [];
            if (isset($order->cart_data['product'])) {
                // Отримайте товар з бази даних або з cart_data
                $productSku = "PROD-" .  $order->cart_data['product']['keycrm_id'];
//                dd($productSku);

                if ($productSku) {
                    $products[] = [
                        "sku" => $productSku, // або $product->sku
                        "price" => (float)$order->cart_data['product']['price'],
                        "purchased_price" => (float)($order->cart_data['product']['price']), // собівартість
                        "discount_percent" => 0,
                        "discount_amount" => 0,
                        "quantity" => $order->cart_data['quantity'],
                        "unit_type" => "шт", // одиниця виміру
                        "name" => $order->cart_data['product']['name'], // назва товару
                        "picture" => $product->image_url ?? "", // URL зображення

                    ];
                }
            }

            // Створіть більш читабельну адресу
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

            // Зберігаємо ID замовлення в keyCRM
            $keyCrmOrderId = $orderResponse['id'] ?? null;

            if ($keyCrmOrderId) {
                // Оновлюємо наше замовлення з ID keyCRM
                $order->update(['keycrm_order_id' => $keyCrmOrderId]);

                // Спробуємо автоматично створити ТТН
                $ttnResponse = $this->createTtn($keyCrmOrderId);

                if ($ttnResponse && isset($ttnResponse['tracking_number'])) {
                    // Зберігаємо трекінг номер
                    $order->update(['tracking_number' => $ttnResponse['tracking_number']]);

                    // Можете надіслати SMS або email з трекінг номером
//                    $this->sendTrackingNotification($order, $ttnResponse['tracking_number']);
                }
            }

            return $orderResponse;

        } catch (\Exception $exception) {
            Log::error('KeyCRMService error: ' . $exception->getMessage(), [
                'order_id' => $order->id ?? null,
            ]);
            return null;
        }
    }

// Метод для створення ТТН
    private function createTtn($keyCrmOrderId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)
                ->post($this->apiUrl . "/v1/order/{$keyCrmOrderId}/create-ttn", [
                    'delivery_service' => 'nova_poshta',
                    'auto_create' => true // для автоматичного створення
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('TTN creation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Створити товар у keyCRM
     */
    public function createProduct(Product $product)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            $productData = [
                'name' => $product->name,
                'description' => $product->description ?? '',
                'price' => (float)$product->price, // Переводимо копійки в гривні
                'currency_code' => 'UAH', // Вказуємо валюту
                'weight' => (float)$product->weight,
                'height' => (float)$product->height,
                'length' => (float)$product->length,
                'width' => (float)$product->width,
                'sku' => 'PROD-' . $product->id,

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
                'response_body' => $response->body() ?? null
            ]);
            return null;
        }
    }

    /**
     * Оновити товар у keyCRM
     */
    public function updateProduct(Product $product)
    {
        try {
            if (empty($this->apiKey) || empty($this->apiUrl)) {
                throw new \Exception('API credentials are not configured');
            }

            if (!$product->keycrm_id) {
                return $this->createProduct($product);
            }

            $productData = [
                'name' => $product->name,
                'description' => $product->description ?? '',
                'price' => (float)$product->price, // Переводимо копійки в гривні
                'currency_code' => 'UAH', // Вказуємо валюту
                'weight' => (float)$product->weight,
                'height' => (float)$product->height,
                'length' => (float)$product->length,
                'width' => (float)$product->width,
                'sku' => 'PROD-' . $product->id,

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
                'response_body' => $response->body() ?? null
            ]);
            return null;
        }
    }


}
