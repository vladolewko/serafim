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

    /**
     * Отримання замовлення за референсом
     */
    public function getOrderByReference($orderReference)
    {
        return Order::where('order_reference', $orderReference)->first();
    }

}




//
//namespace App\Services;
//
//use App\Models\Order;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Session;
//
//class OrderService
//{
//    private $novaPostService;
//    private $wayForPayService;
//    private TelegramBotService $telegramBotService;
//    private KeyCrmService $keyCrmService;
//
//    public function __construct(NovaPostService $novaPostService, WayForPayService $wayForPayService, TelegramBotService $telegramBotService, KeyCrmService $keyCrmService)
//    {
//        $this->novaPostService = $novaPostService;
//        $this->wayForPayService = $wayForPayService;
//        $this->telegramBotService = $telegramBotService;
//        $this->keyCrmService = $keyCrmService;
//    }
//
//    /**
//     * Розрахунок вартості доставки
//     */
//    public function calculateDeliveryCost($cart, $settlementRef)
//    {
//        $quantity = $cart['quantity'];
//        $weight = $cart['product']->weight;
//        $total = (int)$cart['total'];
//
//        if ($weight <= 0 || $total <= 0) {
//            return -1;
//        }
//
//        return $this->novaPostService->getServiceCosts($settlementRef, $weight, $total, $quantity);
//    }
//
//    /**
//     * Обробка замовлення
//     */
//    public function processOrder($validated, $cart, $data)
//    {
////        $phoneForApi = preg_replace('/[^\d]/', '', $validated['phone']);
//
////        $counterparty = $this->novaPostService->createCounterparty([
////            'name' => trim($validated['name']),
////            'surname' => trim($validated['surname']),
////            'phone' => $phoneForApi,
////            'email' => strtolower(trim($validated['email']))
////        ]);
//
////        if (!$counterparty || empty($counterparty['Ref'])) {
////            return ['success' => false, 'message' => 'Не вдалося створити контрагента'];
////        }
//
//        if ($validated['payment'] == 'cash') {
////            return $this->processCashOrder($validated, $cart, $data, $counterparty);
//            return $this->processCashOrder($validated, $cart, $data);
//        }
//
//        if ($validated['payment'] == 'card') {
////            return $this->processCardOrder($validated, $cart, $data, $counterparty);
//            return $this->processCardOrder($validated, $cart, $data);
//        }
//
//        return ['success' => false, 'message' => 'Невідомий тип оплати'];
//    }
//
//    /**
//     * Обробка готівкового замовлення
//     */
////    private function processCashOrder($validated, $cart, $data, $counterparty)
//    private function processCashOrder($validated, $cart, $data)
//    {
////        $order = $this->createOrder($validated, $cart, $data, $counterparty, 'pending');
//        $order = $this->createOrder($validated, $cart, $data, 'pending');
//
////        $ttn = $this->novaPostService->createTTN([
////            'settlement' => $data['settlement'],
////            'warehouse' => $data['warehouse'],
////            'counterparty_ref' => $counterparty['Ref'],
////            'contact_person_ref' => $counterparty['ContactPersonRef'],
////            'phone' => preg_replace('/[^\d]/', '', $validated['phone']),
////            'name' => trim($validated['name']),
////            'surname' => trim($validated['surname']),
////        ], $cart, 'cash');
////
////        if (!$ttn || (isset($ttn['success']) && !$ttn['success'])) {
////            $order->delete();
////            return ['success' => false, 'message' => 'Не вдалося створити ТТН'];
////        }
//
////        $ttnNumber = $ttn['IntDocNumber'] ?? $ttn['Number'] ?? 'Невідомий номер';
////        $order->addTTNData($ttnNumber, $ttn);
//        $this->keyCrmService->sendOrderToCrm($order);
//
//
////        try {
////            Log::info("Starting Telegram notification for order #{$order->id}");
//
////            $this->telegramBotService->sendOrderToTelegram($order);
//
////            if ($result) {
////                Log::info("Order #{$order->id} successfully sent to Telegram");
////            } else {
////                Log::error("Failed to send order #{$order->id} to Telegram - will not retry automatically");
////
////                // Опціонально: відправити email адміну як резервний канал
////                // Mail::to('admin@example.com')->send(new OrderNotification($order));
////            }
//
////        } catch (\Exception $e) {
////            Log::error("Exception while sending order #{$order->id} to Telegram: " . $e->getMessage(), [
////                'order_id' => $order->id,
////                'exception' => $e->getTraceAsString()
////            ]);
////        }
//        Session::forget(['nova_post_data', 'cart']);
//
//        return [
//            'success' => true,
////            'ttn_number' => $order->ttn_number,
//            'message' => 'ТТН успішно створено'
//        ];
//    }
//
//    /**
//     * Обробка картового замовлення
//     */
////    private function processCardOrder($validated, $cart, $data, $counterparty)
//    private function processCardOrder($validated, $cart, $data)
//    {
//        $orderReference = Order::generateOrderReference();
////        $order = $this->createPendingOrder($validated, $cart, $data, $counterparty, $orderReference);
//        $order = $this->createPendingOrder($validated, $cart, $data, $orderReference);
//
//        Session::forget(['cart']);
////        Session::forget(['nova_post_data', 'cart']);
//
//        return [
//            'success' => true,
//            'payment_type' => 'card',
////            'wayforpay_data' => $this->wayForPayService->preparePaymentData($validated, $cart, $data, $orderReference)
//            'wayforpay_data' => $this->wayForPayService->preparePaymentData($validated, $cart, $data)
//        ];
//    }
//
//    /**
//     * Створення замовлення
//     */
////    private function createOrder($validated, $cart, $data, $counterparty, $status = 'pending')
//    private function createOrder($validated, $cart, $data, $status = 'pending')
//    {
////        $weight = $cart['product']->weight * $cart['quantity'];
//        $total = (int)$cart['total'];
//        $deliveryCost = $data['deliveryCost'];
//        $totalAmount = $total + $deliveryCost;
//
//        return Order::create([
//            'order_reference' => Order::generateOrderReference(),
//            'status' => $status,
//            'payment_type' => $validated['payment'],
//            'payment_status' => 'pending',
//            'customer_name' => trim($validated['name']),
//            'customer_surname' => trim($validated['surname']),
//            'customer_phone' => $validated['phone'],
//            'customer_email' => strtolower(trim($validated['email'])),
//            'settlement_ref' => $data['settlement'],
//            'warehouse_ref' => $data['warehouse'],
////            'counterparty_ref' => $counterparty['Ref'],
////            'contact_person_ref' => $counterparty['ContactPersonRef'],
//            'cart_data' => $cart,
//            'product_total' => $total,
//            'delivery_cost' => $deliveryCost,
//            'total_amount' => $totalAmount
//        ]);
//    }
//
//    /**
//     * Створення замовлення в очікуванні оплати
//     */
////    private function createPendingOrder($validated, $cart, $data, $counterparty, $orderReference)
//    private function createPendingOrder($validated, $cart, $data, $orderReference)
//    {
////        $weight = $cart['product']->weight * $cart['quantity'];
//        $total = (int)$cart['total'];
//        $deliveryCost = $data['deliveryCost'];
//        $totalAmount = $total + $deliveryCost;
//
//        return Order::create([
//            'order_reference' => $orderReference,
//            'status' => 'pending_payment',
//            'payment_type' => $validated['payment'],
//            'payment_status' => 'pending',
//            'customer_name' => trim($validated['name']),
//            'customer_surname' => trim($validated['surname']),
//            'customer_phone' => $validated['phone'],
//            'customer_email' => strtolower(trim($validated['email'])),
//            'settlement_ref' => $data['settlement'],
//            'warehouse_ref' => $data['warehouse'],
////            'counterparty_ref' => $counterparty['Ref'],
////            'contact_person_ref' => $counterparty['ContactPersonRef'],
//            'cart_data' => $cart,
//            'product_total' => $total,
//            'delivery_cost' => $deliveryCost,
//            'total_amount' => $totalAmount
//        ]);
//    }
//
//    /**
//     * Обробка callback оплати
//     */
//    public function handlePaymentCallback($data)
//    {
//        $orderReference = $data['orderReference'];
//        $order = Order::where('order_reference', $orderReference)->first();
//
//        if (!$order) {
//            throw new \Exception('Order not found');
//        }
//
//        if ($data['transactionStatus'] === 'Approved') {
//            $order->update([
//                'payment_status' => 'paid',
//                'status' => 'paid',
//                'payment_date' => now()
//            ]);
//
////            $this->createTTNAfterPayment($order);
//        } else {
//            $order->update([
//                'payment_status' => 'failed',
//                'status' => 'failed'
//            ]);
//        }
//
//        return true;
//    }
//
////    /**
////     * Створення ТТН після оплати
////     */
////    private function createTTNAfterPayment($order)
////    {
////        try {
////            if ($order->ttn_number) {
////                return;
////            }
////
////            $requiredFields = [
////                'settlement_ref' => $order->settlement_ref,
////                'warehouse_ref' => $order->warehouse_ref,
////                'counterparty_ref' => $order->counterparty_ref,
////                'contact_person_ref' => $order->contact_person_ref,
////            ];
////
////            foreach ($requiredFields as $field => $value) {
////                if (empty($value)) {
////                    throw new \Exception("Відсутнє обов'язкове поле: {$field}");
////                }
////            }
////
////            if (empty($order->cart_data)) {
////                throw new \Exception('Відсутні дані кошика');
////            }
////
////            $ttnData = [
////                'settlement' => $order->settlement_ref,
////                'warehouse' => $order->warehouse_ref,
////                'counterparty_ref' => $order->counterparty_ref,
////                'contact_person_ref' => $order->contact_person_ref,
////                'phone' => preg_replace('/[^\d]/', '', $order->customer_phone),
////                'name' => $order->customer_name,
////                'surname' => $order->customer_surname,
////            ];
////
////            $ttnResult = $this->novaPostService->createTTN($ttnData, $order->cart_data, 'card');
////
////            if ($ttnResult && (!isset($ttnResult['success']) || $ttnResult['success'] !== false)) {
////                $ttnNumber = $ttnResult['IntDocNumber'] ?? $ttnResult['Number'] ?? null;
////
////                if ($ttnNumber) {
////                    $order->addTTNData($ttnNumber, $ttnResult);
////                    $order->update(['status' => 'processing']);
////
////                    try {
////                        Log::info("Starting Telegram notification for order #{$order->id}");
////
////                        $result = $this->telegramBotService->sendOrderToTelegram($order);
////
////                        if ($result) {
////                            Log::info("Order #{$order->id} successfully sent to Telegram");
////                        } else {
////                            Log::error("Failed to send order #{$order->id} to Telegram - will not retry automatically");
////
////                            // Опціонально: відправити email адміну як резервний канал
////                            // Mail::to('admin@example.com')->send(new OrderNotification($order));
////                        }
////
////                    } catch (\Exception $e) {
////                        Log::error("Exception while sending order #{$order->id} to Telegram: " . $e->getMessage(), [
////                            'order_id' => $order->id,
////                            'exception' => $e->getTraceAsString()
////                        ]);
////                    }
////                } else {
////                    throw new \Exception('ТТН створено, але номер не отримано');
////                }
////
////            } else {
////                throw new \Exception('Не вдалося створити ТТН: ' . json_encode($ttnResult));
////            }
////
////        } catch (\Exception $e) {
////            // Логування помилки без припинення роботи
////        }
////    }
//}
