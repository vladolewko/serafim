<?php

namespace App\Services;

use App\Models\Order;
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
//            dd($warehouseData);

            $deliveryServiceId = null;
            if ($order->payment_type == 'cash') {
                $deliveryServiceId = 1; // ID для наложеного платежу
            } elseif ($order->payment_type == 'card') {
                $deliveryServiceId = 2; // ID для оплати карткою
            }
            $customerName = trim($order->customer_name . ' ' . $order->customer_surname);

            $products = [];
            if (isset($order->cart_data['product'])) {
                $products[] = [
                    'name' => $order->cart_data['product']['name'] ?? '',
                    'quantity' => (int)($order->cart_data['quantity'] ?? 1),
                    'price' => (float)($order->cart_data['product']['price'] ?? 0),
                    'weight' => (float)($order->cart_data['product']['weight'] ?? 1),
//                    'length' => (float)($order->cart_data['product']['length'] ?? 0),
//                    'width' => (float)($order->cart_data['product']['width'] ?? 0),
//                    'height' => (float)($order->cart_data['product']['height'] ?? 0),
                ];
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

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30) // додав timeout
            ->post($this->apiUrl . '/v1/order', $orderData);
//            dd($response->body()); // Для дебагу, видаліть або закоментуйте в продакшені

            if (!$response->successful()) {
                $errorMessage = $response->json()['message'] ?? 'Unknown API error';
                throw new \Exception($errorMessage);
            }

            return $response->json();

        } catch (\Exception $exception) {
            Log::error('KeyCRMService error: ' . $exception->getMessage(), [
                'order_id' => $order->id ?? null,
                'response_body' => $response->body() ?? null
            ]);
            return null;
        }
    }
}
