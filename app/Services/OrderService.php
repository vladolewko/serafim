<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OrderService
{
    private $novaPostService;
    private $wayForPayService;
    private ProductService $productService;
    private KeyCrmService $keyCrmService;

    public function __construct(NovaPostService $novaPostService, WayForPayService $wayForPayService, KeyCrmService $keyCrmService, ProductService $productService)
    {
        $this->productService = $productService;
        $this->novaPostService = $novaPostService;
        $this->wayForPayService = $wayForPayService;
        $this->keyCrmService = $keyCrmService;
    }

    /**
     * Розрахунок вартості доставки
     */
    public function calculateDeliveryCost($cart, $settlementRef)
    {
        $quantity = $cart['quantity'];
        $product = $this->productService->getById($cart['productId']);
        $weight = $product->weight * $quantity;
        $total = (int)$cart['total'];

        if ($weight <= 0 || $total <= 0) {
            return -1;
        }

        return $this->novaPostService->getServiceCosts($settlementRef, $weight, $total, $product, $quantity);
    }

    /**
     * Обробка замовлення
     */
    public function processOrder($validated, $cart, $data)
    {
        try {
            if ($validated['payment'] == 'cash') {
                return $this->processCashOrder($validated, $cart, $data);
            }

            if ($validated['payment'] == 'card') {
                return $this->processCardOrder($validated, $cart, $data);
            }

            return ['success' => false, 'message' => 'Невідомий тип оплати'];
        } catch (\Exception $e) {
            Log::error('Order processing failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Помилка при обробці замовлення'];
        }
    }

    /**
     * Обробка готівкового замовлення
     */
    private function processCashOrder($validated, $cart, $data)
    {
        $order = $this->createOrder($validated, $cart, $data, 'pending');

        if (!$order) {
            return ['success' => false, 'message' => 'Помилка при створенні замовлення'];
        }

        $this->keyCrmService->sendOrderToCrm($order);

        Session::forget(['cart']);

        return [
            'success' => true,
            'message' => 'Замовлення успішно створено'
        ];
    }

    /**
     * Обробка картового замовлення
     */
    private function processCardOrder($validated, $cart, $data)
    {
        $orderReference = Order::generateOrderReference();
        $order = $this->createOrder($validated, $cart, $data, 'pending_payment', $orderReference);

        if (!$order) {
            return ['success' => false, 'message' => 'Помилка при створенні замовлення'];
        }

        // Зберігаємо дані для можливості повторної спроби
        Session::put('pending_order_id', $order->id);
        Session::forget(['cart']); // Очищаємо тільки після успішного створення

        // ВАЖЛИВО: Передайте orderReference в WayForPay
        $paymentData = $this->wayForPayService->preparePaymentData($validated, $cart, $data, $orderReference);

        if (!$paymentData) {
            return ['success' => false, 'message' => 'Помилка при підготовці платіжних даних'];
        }

        return [
            'success' => true,
            'payment_type' => 'card',
            'wayforpay_data' => $paymentData,
            'order_reference' => $orderReference
        ];
    }

    /**
     * Уніфікований метод створення замовлення
     */
    private function createOrder($validated, $cart, $data, $status = 'pending', $orderReference = null)
    {
        try {
            $total = (int)$cart['total'];
            $deliveryCost = $data['deliveryCost'];
            $totalAmount = $total + $deliveryCost;

            // Валідація даних
            if ($total <= 0 || $deliveryCost < 0) {
                Log::error('Invalid order amounts', ['total' => $total, 'delivery' => $deliveryCost]);
                return null;
            }

            $orderData = [
                'order_reference' => $orderReference ?: Order::generateOrderReference(),
                'status' => $status,
                'payment_type' => $validated['payment'],
                'payment_status' => 'pending',
                'customer_name' => trim($validated['name']),
                'customer_surname' => trim($validated['surname']),
                'customer_phone' => $validated['phone'],
                'customer_email' => strtolower(trim($validated['email'])),
                'settlement_ref' => $data['settlement'],
                'warehouse_ref' => $data['warehouse'],
                'cart_data' => $cart,
                'product_total' => $total,
                'delivery_cost' => $deliveryCost,
                'total_amount' => $totalAmount
            ];

            return Order::create($orderData);

        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'validated' => $validated,
                'cart' => $cart,
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Обробка callback оплати
     */
    public function handlePaymentCallback($data)
    {
        try {
            $orderReference = $data['orderReference'];
            $order = Order::where('order_reference', $orderReference)->first();

            if (!$order) {
                Log::error('Order not found for payment callback', ['orderReference' => $orderReference]);
                throw new \Exception('Order not found');
            }

            if ($data['transactionStatus'] === 'Approved') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'paid',
                    'payment_date' => now()
                ]);

                // Відправляємо в CRM тільки після успішної оплати
                $this->keyCrmService->sendOrderToCrm($order);

                // Очищаємо session дані
                Session::forget(['nova_post_data', 'pending_order_id']);

            } else {
                $order->update([
                    'payment_status' => 'failed',
                    'status' => 'failed'
                ]);
            }

            return $order;

        } catch (\Exception $e) {
            Log::error('Payment callback processing failed: ' . $e->getMessage(), ['data' => $data]);
            throw $e;
        }
    }
}
