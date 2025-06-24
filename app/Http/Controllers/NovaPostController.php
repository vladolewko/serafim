<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\NovaPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class NovaPostController extends Controller
{
    protected $novaPostService;

    public function __construct(NovaPostService $novaPostService)
    {
        $this->novaPostService = $novaPostService;
    }

    /**
     * Валідація пошуку населених пунктів
     */
    public function searchSettlement(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'
                ]
            ], [
                'search.required' => 'Введіть назву населеного пункту',
                'search.min' => 'Назва повинна містити мінімум 2 символи',
                'search.max' => 'Назва занадто довга (максимум 100 символів)',
                'search.regex' => 'Назва може містити лише українські букви, пробіли, дефіси та апострофи'
            ]);

            $search = trim($validated['search']);
            $settlements = $this->novaPostService->searchSettlement($search);

            if (empty($settlements)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Населені пункти з такою назвою не знайдено. Спробуйте ввести частину назви або перевірте правильність написання.',
                    'suggestions' => 'Спробуйте ввести: "Київ", "Львів", "Одеса" або частину назви вашого міста'
                ]);
            }

            Session::put('nova_post_data', ['search' => $search]);

            return response()->json([
                'success' => true,
                'settlements' => $settlements,
                'addressData' => ['search' => $search],
                'count' => count($settlements)
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->errors()->first(),
                'validation_errors' => $e->validator->errors()->all()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Settlement search error', [
                'message' => $e->getMessage(),
                'search' => $request->input('search'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Сталася помилка при пошуку населених пунктів. Спробуйте пізніше.'
            ], 500);
        }
    }

    /**
     * Валідація вибору населеного пункту
     */
    public function chooseSettlement(Request $request)
    {
        try {
            $validated = $request->validate([
                'settlement' => [
                    'required',
                    'string',
                    'size:36' // UUID format
                ]
            ], [
                'settlement.required' => 'Оберіть населений пункт зі списку',
                'settlement.size' => 'Некоректний ідентифікатор населеного пункту'
            ]);

            $settlementRef = $validated['settlement'];
            $data = Session::get('nova_post_data', []);

            if (empty($data['search'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Спочатку виконайте пошук населеного пункту'
                ], 400);
            }

            $data['settlement'] = $settlementRef;
            Session::put('nova_post_data', $data);

            $warehouses = $this->novaPostService->getWarehouses($settlementRef);
            $settlements = $this->novaPostService->searchSettlement($data['search']);

            if (empty($warehouses)) {
                return response()->json([
                    'success' => false,
                    'error' => 'У обраному населеному пункті немає доступних відділень Нової Пошти',
                    'suggestion' => 'Спробуйте обрати сусіднє місто або зв\'яжіться з підтримкою'
                ]);
            }

            return response()->json([
                'success' => true,
                'warehouses' => $warehouses,
                'settlements' => $settlements,
                'addressData' => $data,
                'warehouses_count' => count($warehouses)
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->errors()->first(),
                'validation_errors' => $e->validator->errors()->all()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Settlement choose error', [
                'message' => $e->getMessage(),
                'settlement' => $request->input('settlement'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Помилка при завантаженні відділень. Спробуйте оновити сторінку.'
            ], 500);
        }
    }

    /**
     * Валідація вибору відділення
     */
    public function setWarehouse(Request $request)
    {
        try {
            $validated = $request->validate([
                'warehouse' => [
                    'required',
                    'string',
                    'size:36' // UUID format
                ]
            ], [
                'warehouse.required' => 'Оберіть відділення або поштомат',
                'warehouse.size' => 'Некоректний ідентифікатор відділення'
            ]);

            $warehouseRef = $validated['warehouse'];
            $data = Session::get('nova_post_data', []);

            // Перевірка чи є всі необхідні дані в сесії
            if (empty($data['settlement']) || empty($data['search'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Втрачено дані про населений пункт. Почніть процес заново.'
                ], 400);
            }

            // Перевірка чи є товари в корзині
            $cart = session('cart');
            if (empty($cart) || empty($cart['product']) || empty($cart['quantity'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Корзина порожня. Додайте товари перед оформленням замовлення.',
                    'redirect' => route('home')
                ], 400);
            }

            $data['warehouse'] = $warehouseRef;
            Session::put('nova_post_data', $data);

            $settlements = $this->novaPostService->searchSettlement($data['search']);
            $warehouses = $this->novaPostService->getWarehouses($data['settlement']);

            $settlementRef = $data['settlement'];
            $quantity = $cart['quantity'];
            $weight = $cart['product']->weight;
            $total = (int)$cart['total'];

            // Валідація параметрів для розрахунку доставки
            if ($weight <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Некоректна вага товару. Зв\'яжіться з підтримкою.'
                ], 400);
            }

            if ($total <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Некоректна вартість товару. Зв\'яжіться з підтримкою.'
                ], 400);
            }

            $deliveryCost = $this->novaPostService->getServiceCosts($settlementRef, $weight, $total, $quantity);
//            $deliveryCost = 1;

            if ($deliveryCost < 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Не вдалося розрахувати вартість доставки. Спробуйте обрати інше відділення.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'deliveryCost' => $deliveryCost,
                'productCosts' => $cart['total'],
                'totalAmount' => $cart['total'] + $deliveryCost,
                'addressData' => $data,
                'settlements' => $settlements,
                'warehouses' => $warehouses
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->validator->errors()->first(),
                'validation_errors' => $e->validator->errors()->all()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Warehouse set error', [
                'message' => $e->getMessage(),
                'warehouse' => $request->input('warehouse'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Помилка при розрахунку доставки. Спробуйте ще раз.'
            ], 500);
        }
    }

    public function createCounterparty(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'],
                'surname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'],
                'phone' => ['required', 'string', 'regex:/^\+380\s\d{2}\s\d{3}\s\d{2}\s\d{2}$/'],
                'email' => ['required', 'email', 'max:255'],
                'payment' => ['required', 'in:cash,card']
            ]);

            $cart = session()->get('cart');
            $data = Session::get('nova_post_data', []);

            if (empty($cart)) {
                return response()->json(['success' => false, 'message' => 'Корзина порожня'], 400);
            }

            if (empty($data['settlement']) || empty($data['warehouse'])) {
                return response()->json(['success' => false, 'message' => 'Не обрано адресу доставки'], 400);
            }

            $phoneForApi = preg_replace('/[^\d]/', '', $validated['phone']);

            $counterparty = $this->novaPostService->createCounterparty([
                'name' => trim($validated['name']),
                'surname' => trim($validated['surname']),
                'phone' => $phoneForApi,
                'email' => strtolower(trim($validated['email']))
            ]);

            if (!$counterparty || empty($counterparty['Ref'])) {
                return response()->json(['success' => false, 'message' => 'Не вдалося створити контрагента'], 400);
            }

            if ($validated['payment'] == 'cash') {
                // Для готівкової оплати створюємо замовлення відразу
                $order = $this->createOrder($validated, $cart, $data, $counterparty);

                $ttn = $this->novaPostService->createTTN([
                    'settlement' => $data['settlement'],
                    'warehouse' => $data['warehouse'],
                    'counterparty_ref' => $counterparty['Ref'],
                    'contact_person_ref' => $counterparty['ContactPersonRef'],
                    'phone' => $phoneForApi,
                    'name' => trim($validated['name']),
                    'surname' => trim($validated['surname']),
                ], $cart, 'cash');

                if (!$ttn || (isset($ttn['success']) && !$ttn['success'])) {
                    $order->delete();
                    return response()->json(['success' => false, 'message' => 'Не вдалося створити ТТН'], 400);
                }

                $ttnNumber = $ttn['IntDocNumber'] ?? $ttn['Number'] ?? 'Невідомий номер';
                $order->addTTNData($ttnNumber, $ttn);

                Session::forget(['nova_post_data', 'cart']);

                return response()->json([
                    'success' => true,
                    'ttn_number' => $order->ttn_number,
                    'message' => 'ТТН успішно створено'
                ]);
            }

            if ($validated['payment'] == 'card') {
                // Для картки зберігаємо дані в сесії для створення замовлення після оплати
                $orderData = [
                    'validated' => $validated,
                    'cart' => $cart,
                    'nova_post_data' => $data,
                    'counterparty' => $counterparty
                ];

                Session::put('pending_order_data', $orderData);

                // Генеруємо унікальний номер замовлення для WayForPay
                $orderReference = Order::generateOrderReference();
                Session::put('pending_order_reference', $orderReference);

                return response()->json([
                    'success' => true,
                    'payment_type' => 'card',
                    'wayforpay_data' => $this->prepareWayForPayData($validated, $cart, $data, $orderReference)
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка валідації: ' . $e->validator->errors()->first()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Order creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Сталася помилка при створенні замовлення'
            ], 500);
        }
    }

    private function createOrder($validated, $cart, $data, $counterparty)
    {
        $weight = $cart['product']->weight * $cart['quantity'];
        $total = (int)$cart['total'];
        $deliveryCost = 0;
        $totalAmount = $total + $deliveryCost;

        return Order::create([
            'order_reference' => Order::generateOrderReference(),
            'status' => 'pending',
            'payment_type' => $validated['payment'],
            'payment_status' => $validated['payment'] == 'cash' ? 'pending' : 'pending',
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

    private function prepareWayForPayData($validated, $cart, $data, $orderReference)
    {
        $merchantAccount = config('services.wayforpay.merchant_account');
        $merchantSecretKey = config('services.wayforpay.secret_key');
        // Використовуємо реальний домен з конфігурації
        $merchantDomainName = config('services.wayforpay.url', 'https://serafym.info');

        if (!$merchantAccount || !$merchantSecretKey) {
            throw new \Exception('WayForPay не налаштований');
        }

        $total = (int)$cart['total'];
        $deliveryCost = 0;
        $totalAmount = $total + $deliveryCost;

        $amount = number_format($totalAmount, 2, '.', '');
        $currency = 'UAH';
        $orderDate = time();

        $product = $cart['product'];
        $productName = [is_array($product) ? $product['name'] : $product->name];
        $productPrice = [number_format($total, 2, '.', '')];
        $productCount = [$cart['quantity']];

        $signString = implode(';', [
            $merchantAccount,
            $merchantDomainName,
            $orderReference,
            $orderDate,
            $amount,
            $currency,
            implode(';', $productName),
            implode(';', $productCount),
            implode(';', $productPrice)
        ]);

        $merchantSignature = hash_hmac('md5', $signString, $merchantSecretKey);

        return [
            'merchantAccount' => $merchantAccount,
            'merchantDomainName' => $merchantDomainName,
            'orderReference' => $orderReference,
            'orderDate' => $orderDate,
            'amount' => $amount,
            'currency' => $currency,
            'productName' => $productName,
            'productCount' => $productCount,
            'productPrice' => $productPrice,
            'clientFirstName' => trim($validated['name']),
            'clientLastName' => trim($validated['surname']),
            'clientEmail' => strtolower(trim($validated['email'])),
            'clientPhone' => preg_replace('/[^\d]/', '', $validated['phone']),
            'language' => 'UA',
            // Виправляємо URL колбеку
            'serviceUrl' => rtrim($merchantDomainName, '/') . '/api/orders/payment/callback',
            'merchantSignature' => $merchantSignature
        ];
    }

    public function paymentCallback(Request $request)
    {
        try {
            // Логуємо всі отримані дані
            Log::info('WayForPay callback received', [
                'headers' => $request->headers->all(),
                'data' => $request->all(),
                'ip' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]);

            $data = $request->all();

            // Перевіряємо наявність обов'язкових полів
            if (!isset($data['orderReference']) || !isset($data['transactionStatus'])) {
                Log::error('Missing required fields in callback', $data);
                return response('Missing required fields', 400);
            }

            if (!$this->verifySignature($data)) {
                Log::error('Invalid signature in callback', $data);
                return response('Invalid signature', 400);
            }

            $orderReference = $data['orderReference'];

            // Перевіряємо чи є дані в сесії для цього замовлення
            $pendingOrderReference = Session::get('pending_order_reference');
            $pendingOrderData = Session::get('pending_order_data');

            Log::info('Checking pending order data', [
                'pending_reference' => $pendingOrderReference,
                'received_reference' => $orderReference,
                'has_pending_data' => !empty($pendingOrderData)
            ]);

            if ($pendingOrderReference !== $orderReference || !$pendingOrderData) {
                Log::error('Order reference mismatch or no pending order data', [
                    'expected' => $pendingOrderReference,
                    'received' => $orderReference,
                    'has_data' => !empty($pendingOrderData)
                ]);
                return response('Order data not found', 404);
            }

            if ($data['transactionStatus'] === 'Approved') {
                Log::info('Payment approved, creating order', ['order_reference' => $orderReference]);

                // Створюємо замовлення тільки після успішної оплати
                $order = $this->createOrder(
                    $pendingOrderData['validated'],
                    $pendingOrderData['cart'],
                    $pendingOrderData['nova_post_data'],
                    $pendingOrderData['counterparty']
                );

                // Оновлюємо номер замовлення на той, що використовувався в WayForPay
                $order->update([
                    'order_reference' => $orderReference,
                    'payment_status' => 'paid',
                    'status' => 'paid'
                ]);

                // Створюємо ТТН після успішної оплати
                $this->createTTNAfterPayment($order);

                // Очищуємо сесію
                Session::forget(['pending_order_data', 'pending_order_reference', 'nova_post_data', 'cart']);

                Log::info('Order created successfully after payment', [
                    'order_id' => $order->id,
                    'order_reference' => $orderReference
                ]);
            } else {
                Log::info('Payment failed or declined', [
                    'order_reference' => $orderReference,
                    'status' => $data['transactionStatus'],
                    'reason_code' => $data['reasonCode'] ?? 'Unknown'
                ]);

                // Якщо оплата не пройшла, очищуємо тільки дані замовлення
                Session::forget(['pending_order_data', 'pending_order_reference']);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return response('Error', 500);
        }
    }

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

            if ($ttnResult && (!isset($ttnResult['success']) || $ttnResult['success'] === true)) {
                $ttnNumber = $ttnResult['IntDocNumber'] ?? $ttnResult['Number'] ?? null;

                if ($ttnNumber) {
                    $order->addTTNData($ttnNumber, $ttnResult);
                }
            }

        } catch (\Exception $e) {
            Log::error('TTN creation error: ' . $e->getMessage() . ' Order: ' . $order->order_reference);
        }
    }

    public function getOrderStatus($orderReference)
    {
        try {
            $order = Order::where('order_reference', $orderReference)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Замовлення не знайдено'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_reference' => $order->order_reference,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'ttn_number' => $order->ttn_number,
                    'created_at' => $order->created_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Помилка при отриманні статусу замовлення'
            ], 500);
        }
    }

    private function verifySignature($data)
    {
        $merchantSecretKey = config('services.wayforpay.secret_key');

        $signString = implode(';', [
            $data['merchantAccount'] ?? '',
            $data['orderReference'] ?? '',
            $data['amount'] ?? '',
            $data['currency'] ?? '',
            $data['authCode'] ?? '',
            $data['cardPan'] ?? '',
            $data['transactionStatus'] ?? '',
            $data['reasonCode'] ?? ''
        ]);

        $expectedSignature = hash_hmac('md5', $signString, $merchantSecretKey);
        return $expectedSignature === ($data['merchantSignature'] ?? '');
    }

    public function setupSender(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:50'],
                'surname' => ['required', 'string', 'min:2', 'max:50'],
                'phone' => ['required', 'string'],
                'city' => ['required', 'string', 'min:2', 'max:100']
            ]);
            Log::error('Валідація пройшла успішно: ');


            $result = $this->novaPostService->setupSender($validated);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Відправник налаштований успішно' : 'Помилка налаштування відправника'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка валідації: ' . $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Sender setup error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Помилка при налаштуванні відправника'
            ], 500);
        }
    }

    public function checkStatus()
    {
        try {
            $senderStatus = $this->novaPostService->checkSenderSetup();
            $apiTest = $this->novaPostService->testApiKey();

            return response()->json([
                'api_key' => $apiTest,
                'sender_setup' => $senderStatus,
                'status' => 'ok'
            ]);
        } catch (\Exception $e) {
            Log::error('Status check error: ' . $e->getMessage());
            return response()->json([
                'api_key' => false,
                'sender_setup' => false,
                'status' => 'error',
                'message' => 'Помилка перевірки статусу сервісу'
            ], 500);
        }
    }
}
