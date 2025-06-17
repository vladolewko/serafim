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
            $weight = $cart['product']->weight * $cart['quantity'];
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

            $deliveryCost = $this->novaPostService->getServiceCosts($settlementRef, $weight, $total);

            if ($deliveryCost === false || $deliveryCost < 0) {
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

            $weight = $cart['product']->weight * $cart['quantity'];
            $total = (int)$cart['total'];
            $deliveryCost = 0;
            $totalAmount = $total + $deliveryCost;

            $order = Order::create([
                'order_reference' => Order::generateOrderReference(),
                'status' => 'pending',
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

            if ($validated['payment'] == 'cash') {
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
                return response()->json([
                    'success' => true,
                    'payment_type' => 'card',
                    'wayforpay_data' => $this->prepareWayForPayData($order)
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

    private function prepareWayForPayData($order)
    {
        $merchantAccount = config('services.wayforpay.merchant_account');
        $merchantSecretKey = config('services.wayforpay.secret_key');
        $merchantDomainName = 'www.market.u';

        if (!$merchantAccount || !$merchantSecretKey) {
            throw new \Exception('WayForPay не налаштований');
        }

        $orderReference = $order->order_reference;
        $amount = number_format($order->total_amount, 2, '.', '');
        $currency = 'UAH';
        $orderDate = time();

        $product = $order->cart_data['product'];
        $productName = [is_array($product) ? $product['name'] : $product->name];
        $productPrice = [number_format($order->product_total, 2, '.', '')];
        $productCount = [$order->cart_data['quantity']];

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
            'clientFirstName' => $order->customer_name,
            'clientLastName' => $order->customer_surname,
            'clientEmail' => $order->customer_email,
            'clientPhone' => preg_replace('/[^\d]/', '', $order->customer_phone),
            'language' => 'UA',
            'serviceUrl' => $merchantDomainName . '/api/orders/payment/callback',
            'merchantSignature' => $merchantSignature
        ];
    }

    public function paymentCallback(Request $request)
    {
        try {
            $data = $request->all();

            if (!$this->verifySignature($data)) {
                return response('Invalid signature', 400);
            }

            $order = Order::where('order_reference', $data['orderReference'])->first();
            if (!$order) {
                return response('Order not found', 404);
            }

            if ($data['transactionStatus'] === 'Approved') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'paid'
                ]);

                $this->createTTNAfterPayment($order);
            }

            return response('OK');

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
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
                'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'],
                'surname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'],
                'phone' => ['required', 'string', 'regex:/^380\d{9}$/'],
                'city' => ['required', 'string', 'min:2', 'max:100']
            ]);

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
