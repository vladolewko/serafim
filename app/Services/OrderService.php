<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OrderService
{
    private $novaPostService;
    private $wayForPayService;
    private TelegramBotService $telegramBotService;

    public function __construct(NovaPostService $novaPostService, WayForPayService $wayForPayService, TelegramBotService $telegramBotService)
    {
        $this->novaPostService = $novaPostService;
        $this->wayForPayService = $wayForPayService;
        $this->telegramBotService = $telegramBotService;
    }

    /**
     * Розрахунок вартості доставки
     */
    public function calculateDeliveryCost($cart, $settlementRef)
    {
        $quantity = $cart['quantity'];
        $weight = $cart['product']->weight;
        $total = (int)$cart['total'];

        if ($weight <= 0 || $total <= 0) {
            return -1;
        }

        return $this->novaPostService->getServiceCosts($settlementRef, $weight, $total, $quantity);
    }

    /**
     * Обробка замовлення
     */
    public function processOrder($validated, $cart, $data)
    {
        $phoneForApi = preg_replace('/[^\d]/', '', $validated['phone']);

        $counterparty = $this->novaPostService->createCounterparty([
            'name' => trim($validated['name']),
            'surname' => trim($validated['surname']),
            'phone' => $phoneForApi,
            'email' => strtolower(trim($validated['email']))
        ]);

        if (!$counterparty || empty($counterparty['Ref'])) {
            return ['success' => false, 'message' => 'Не вдалося створити контрагента'];
        }

        if ($validated['payment'] == 'cash') {
            return $this->processCashOrder($validated, $cart, $data, $counterparty);
        }

        if ($validated['payment'] == 'card') {
            return $this->processCardOrder($validated, $cart, $data, $counterparty);
        }

        return ['success' => false, 'message' => 'Невідомий тип оплати'];
    }

    /**
     * Обробка готівкового замовлення
     */
    private function processCashOrder($validated, $cart, $data, $counterparty)
    {
        $order = $this->createOrder($validated, $cart, $data, $counterparty, 'pending');

        $ttn = $this->novaPostService->createTTN([
            'settlement' => $data['settlement'],
            'warehouse' => $data['warehouse'],
            'counterparty_ref' => $counterparty['Ref'],
            'contact_person_ref' => $counterparty['ContactPersonRef'],
            'phone' => preg_replace('/[^\d]/', '', $validated['phone']),
            'name' => trim($validated['name']),
            'surname' => trim($validated['surname']),
        ], $cart, 'cash');

        if (!$ttn || (isset($ttn['success']) && !$ttn['success'])) {
            $order->delete();
            return ['success' => false, 'message' => 'Не вдалося створити ТТН'];
        }

        $ttnNumber = $ttn['IntDocNumber'] ?? $ttn['Number'] ?? 'Невідомий номер';
        $order->addTTNData($ttnNumber, $ttn);

            try {
                $this->telegramBotService->sendOrderToTelegram($order);
            } catch (\Exception $e) {
                Log::error($e->getMessage() . 'помилка при відправці замовлення в Telegram');
            }


        Session::forget(['nova_post_data', 'cart']);

        return [
            'success' => true,
            'ttn_number' => $order->ttn_number,
            'message' => 'ТТН успішно створено'
        ];
    }

    /**
     * Обробка картового замовлення
     */
    private function processCardOrder($validated, $cart, $data, $counterparty)
    {
        $orderReference = Order::generateOrderReference();
        $order = $this->createPendingOrder($validated, $cart, $data, $counterparty, $orderReference);

        Session::forget(['nova_post_data', 'cart']);

        return [
            'success' => true,
            'payment_type' => 'card',
            'wayforpay_data' => $this->wayForPayService->preparePaymentData($validated, $cart, $data, $orderReference)
        ];
    }

    /**
     * Створення замовлення
     */
    private function createOrder($validated, $cart, $data, $counterparty, $status = 'pending')
    {
//        $weight = $cart['product']->weight * $cart['quantity'];
        $total = (int)$cart['total'];
        $deliveryCost = $data['deliveryCost'];
        $totalAmount = $total + $deliveryCost;

        return Order::create([
            'order_reference' => Order::generateOrderReference(),
            'status' => $status,
            'payment_type' => $validated['payment'],
            'payment_status' => 'pending',
            'customer_name' => trim($validated['name']),
            'customer_surname' => trim($validated['surname']),
            'customer_phone' => $validated['phone'],
            'customer_email' => strtolower(trim($validated['email'])),
            'settlement_ref' => $data['settlement'],
            'warehouse_ref' => $data['warehouse'],
            'counterparty_ref' => $counterparty['Ref'],
            'contact_person_ref' => $counterparty['ContactPersonRef'],
            'cart_data' => $cart,
            'product_total' => $total,
            'delivery_cost' => $deliveryCost,
            'total_amount' => $totalAmount
        ]);
    }

    /**
     * Створення замовлення в очікуванні оплати
     */
    private function createPendingOrder($validated, $cart, $data, $counterparty, $orderReference)
    {
        $weight = $cart['product']->weight * $cart['quantity'];
        $total = (int)$cart['total'];
        $deliveryCost = 0;
        $totalAmount = $total + $deliveryCost;

        return Order::create([
            'order_reference' => $orderReference,
            'status' => 'pending_payment',
            'payment_type' => $validated['payment'],
            'payment_status' => 'pending',
            'customer_name' => trim($validated['name']),
            'customer_surname' => trim($validated['surname']),
            'customer_phone' => $validated['phone'],
            'customer_email' => strtolower(trim($validated['email'])),
            'settlement_ref' => $data['settlement'],
            'warehouse_ref' => $data['warehouse'],
            'counterparty_ref' => $counterparty['Ref'],
            'contact_person_ref' => $counterparty['ContactPersonRef'],
            'cart_data' => $cart,
            'product_total' => $total,
            'delivery_cost' => $deliveryCost,
            'total_amount' => $totalAmount
        ]);
    }

    /**
     * Обробка callback оплати
     */
    public function handlePaymentCallback($data)
    {
        $orderReference = $data['orderReference'];
        $order = Order::where('order_reference', $orderReference)->first();

        if (!$order) {
            throw new \Exception('Order not found');
        }

        if ($data['transactionStatus'] === 'Approved') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'paid',
                'payment_date' => now()
            ]);

            $this->createTTNAfterPayment($order);
        } else {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'failed'
            ]);
        }

        return true;
    }

    /**
     * Створення ТТН після оплати
     */
    private function createTTNAfterPayment($order)
    {
        try {
            if ($order->ttn_number) {
                return;
            }

            $requiredFields = [
                'settlement_ref' => $order->settlement_ref,
                'warehouse_ref' => $order->warehouse_ref,
                'counterparty_ref' => $order->counterparty_ref,
                'contact_person_ref' => $order->contact_person_ref,
            ];

            foreach ($requiredFields as $field => $value) {
                if (empty($value)) {
                    throw new \Exception("Відсутнє обов'язкове поле: {$field}");
                }
            }

            if (empty($order->cart_data)) {
                throw new \Exception('Відсутні дані кошика');
            }

            $ttnData = [
                'settlement' => $order->settlement_ref,
                'warehouse' => $order->warehouse_ref,
                'counterparty_ref' => $order->counterparty_ref,
                'contact_person_ref' => $order->contact_person_ref,
                'phone' => preg_replace('/[^\d]/', '', $order->customer_phone),
                'name' => $order->customer_name,
                'surname' => $order->customer_surname,
            ];

            $ttnResult = $this->novaPostService->createTTN($ttnData, $order->cart_data, 'card');

            if ($ttnResult && (!isset($ttnResult['success']) || $ttnResult['success'] !== false)) {
                $ttnNumber = $ttnResult['IntDocNumber'] ?? $ttnResult['Number'] ?? null;

                if ($ttnNumber) {
                    $order->addTTNData($ttnNumber, $ttnResult);
                    $order->update(['status' => 'processing']);

                    try {
                        $this->telegramBotService->sendOrderToTelegram($order);
                    } catch (\Exception $e) {
                        Log::error($e->getMessage() . 'помилка при відправці замовлення в Telegram');
                    }

                } else {
                    throw new \Exception('ТТН створено, але номер не отримано');
                }

            } else {
                throw new \Exception('Не вдалося створити ТТН: ' . json_encode($ttnResult));
            }

        } catch (\Exception $e) {
            // Логування помилки без припинення роботи
        }
    }
}
