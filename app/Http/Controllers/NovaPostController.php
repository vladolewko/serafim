<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\NovaPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class NovaPostController extends Controller
{
    protected $novaPostService;

    public function __construct(NovaPostService $novaPostService)
    {
        $this->novaPostService = $novaPostService;
    }

    public function searchSettlement(Request $request)
    {
        $search = $request->input('search');
        $settlements = $this->novaPostService->searchSettlement($search);

        if (empty($settlements)) {
            return response()->json([
                'success' => false,
                'error' => 'Немає населених пунктів, що відповідають запиту'
            ]);
        }

        Session::put('nova_post_data', ['search' => $search]);

        return response()->json([
            'success' => true,
            'settlements' => $settlements,
            'addressData' => ['search' => $search]
        ]);
    }

    public function chooseSettlement(Request $request)
    {
        $settlementRef = $request->input('settlement');
        $data = Session::get('nova_post_data', []);
        $data['settlement'] = $settlementRef;
        Session::put('nova_post_data', $data);

        $warehouses = $this->novaPostService->getWarehouses($settlementRef);
        $settlements = $this->novaPostService->searchSettlement($data['search']);

        if (empty($warehouses)) {
            return response()->json([
                'success' => false,
                'error' => 'Немає відділень, що відповідають запиту'
            ]);
        }

        return response()->json([
            'success' => true,
            'warehouses' => $warehouses,
            'settlements' => $settlements,
            'addressData' => $data
        ]);
    }

    public function setWarehouse(Request $request)
    {
        $warehouseRef = $request->input('warehouse');
        $data = Session::get('nova_post_data', []);
        $data['warehouse'] = $warehouseRef;
        Session::put('nova_post_data', $data);

        $settlements = $this->novaPostService->searchSettlement($data['search']);
        $warehouses = $this->novaPostService->getWarehouses($data['settlement']);

        $settlementRef = $data['settlement'];
        $weight = session('cart')['product']->weight * session('cart')['quantity'];
        $total = (int)session('cart')['total'];
        $deliveryCost = $this->novaPostService->getServiceCosts($settlementRef, $weight, $total);

        return response()->json([
            'success' => true,
            'deliveryCost' => $deliveryCost,
            'productCosts' => session('cart')['total'],
            'addressData' => $data,
            'settlements' => $settlements,
            'warehouses' => $warehouses
        ]);
    }

    public function createCounterparty(Request $request)
    {
        try {
            $payment = $request->input('payment');
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'phone' => 'required|string|max:25',
                'email' => 'required|string|max:255'
            ]);

            $cart = session()->get('cart');
            $data = Session::get('nova_post_data', []);

            if (empty($data['settlement']) || empty($data['warehouse'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Не обрано населений пункт або відділення'
                ]);
            }

            $counterparty = $this->novaPostService->createCounterparty($validated);

            $settlementRef = $data['settlement'];
            $weight = $cart['product']->weight * $cart['quantity'];
            $total = (int)$cart['total'];
            $deliveryCost = $this->novaPostService->getServiceCosts($settlementRef, $weight, $total);
            $totalAmount = $total + $deliveryCost;

            $orderReference = Order::generateOrderReference();

            $order = Order::create([
                'order_reference' => $orderReference,
                'status' => 'pending',
                'payment_type' => $payment,
                'payment_status' => $payment === 'cash' ? 'pending' : 'pending',
                'customer_name' => $validated['name'],
                'customer_surname' => $validated['surname'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'settlement_ref' => $data['settlement'],
                'warehouse_ref' => $data['warehouse'],
                'counterparty_ref' => $counterparty['Ref'],
                'contact_person_ref' => $counterparty['ContactPersonRef'],
                'cart_data' => $cart,
                'product_total' => $total,
                'delivery_cost' => $deliveryCost,
                'total_amount' => $totalAmount
            ]);

            if ($payment == 'cash') {
                $ttn = $this->novaPostService->createTTN([
                    'settlement' => $data['settlement'],
                    'warehouse' => $data['warehouse'],
                    'counterparty_ref' => $counterparty['Ref'],
                    'contact_person_ref' => $counterparty['ContactPersonRef'],
                    'phone' => $validated['phone'],
                    'name' => $validated['name'],
                    'surname' => $validated['surname'],
                ], $cart, $payment);

                $order->addTTNData(
                    $ttn['IntDocNumber'] ?? $ttn['Number'] ?? 'Невідомий номер',
                    $ttn
                );

                Session::forget('nova_post_data');
                Session::forget('cart');

                return response()->json([
                    'success' => true,
                    'ttn_number' => $order->ttn_number,
                    'order_reference' => $order->order_reference,
                    'message' => 'ТТН успішно створено'
                ], 200, [], JSON_UNESCAPED_UNICODE);

            } elseif ($payment == 'card') {
                $wayForPayData = $this->generateWayForPayData($validated, $totalAmount, $orderReference);

                $order->wayforpay_data = $wayForPayData;
                $order->save();

                Session::forget('nova_post_data');
                Session::forget('cart');

                return response()->json([
                    'success' => true,
                    'payment_type' => 'card',
                    'order_reference' => $order->order_reference,
                    'wayforpay_data' => $wayForPayData
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка валідації: ' . implode(', ', $e->validator->errors()->all())
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating order in controller', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function generateWayForPayData($data, $amount, $orderReference)
    {
        $amount = 5; // ТЕСТОВА СУМА
        $merchantAccount = 'test_merch_n1';
        $merchantSecret = 'flk3409refn54t54t*FNJRET';
        $currency = 'UAH';
        $orderDate = time();
        $productName = 'Замовлення з інтернет-магазину';

        $signatureString = $merchantAccount . ';' .
            request()->getSchemeAndHttpHost() . ';' .
            $orderReference . ';' .
            $orderDate . ';' .
            $amount . ';' .
            $currency . ';' .
            $productName . ';' .
            1 . ';' .
            $amount;

        $signature = hash_hmac('md5', $signatureString, $merchantSecret);

        Log::info('WayForPay signature data', [
            'order_reference' => $orderReference,
            'string' => $signatureString,
            'signature' => $signature
        ]);

        return [
            'merchantAccount' => $merchantAccount,
            'merchantAuthType' => 'SimpleSignature',
            'merchantDomainName' => request()->getSchemeAndHttpHost(),
            'merchantSignature' => $signature,
            'orderReference' => $orderReference,
            'orderDate' => $orderDate,
            'amount' => $amount,
            'currency' => $currency,
            'productName' => [$productName],
            'productPrice' => [$amount],
            'productCount' => [1],
            'clientFirstName' => $data['name'],
            'clientLastName' => $data['surname'],
            'clientEmail' => $data['email'],
            'defaultPaymentSystem' => 'card',

            'returnUrl' => 'http://localhost:8888/serafim/public/api/payment/success',
//            'returnUrl' => '',
            'serviceUrl' => 'http://localhost:8888/serafim/public/api/payment/callback'
        ];
    }
    public function paymentSuccessPage(Request $request)
    {
        try {
            Log::info('=== PAYMENT SUCCESS PAGE START ===', [
                'method' => $request->method(),
                'path' => $request->path(),
                'all_data' => $request->all(),
                'referer' => $request->header('referer')
            ]);

            if ($request->has('debug')) {
                return response()->json(['message' => 'Controller reached successfully']);
            }

            $orderReference = $request->input('orderReference');

            if (!$orderReference) {
                Log::warning('No order reference provided');

                return response()->json([
                    'success' => false,
                    'message' => 'Не вдалося знайти номер замовлення',
                    'received_data' => $request->all()
                ]);
            }

            $order = Order::findByReference($orderReference);

            if (!$order) {
                Log::warning('Order not found', ['order_reference' => $orderReference]);

                return response()->json([
                    'success' => false,
                    'message' => 'Замовлення не знайдено',
                    'order_reference' => $orderReference
                ]);
            }

            Log::info('Order found, processing payment result', [
                'order_reference' => $orderReference,
                'order_id' => $order->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Замовлення знайдено успішно',
                'order_reference' => $orderReference,
                'payment_data' => $request->all()
            ]);


        } catch (\Exception $e) {
            Log::error('Payment success page error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function paymentFailedPage(Request $request)
    {
        $orderReference = $request->input('orderReference');

        if ($orderReference) {
            $order = Order::findByReference($orderReference);
            if ($order) {
                $order->updatePaymentStatus('failed');
            }
        }

        return view('payment.failed', [
            'error' => 'Платіж не був завершений або був відхилений',
            'order_reference' => $orderReference
        ]);
    }

    public function paymentCallback(Request $request)
    {
        Log::info('Payment callback received', $request->all());

        try {
            $orderReference = $request->input('orderReference');
            $merchantSecret = 'flk3409refn54t54t*FNJRET';

            // Правильна перевірка підпису для callback
            $signatureString = $request->input('merchantAccount', '') . ';' .
                $request->input('orderReference', '') . ';' .
                $request->input('amount', '') . ';' .
                $request->input('currency', 'UAH') . ';' .
                $request->input('authCode', '') . ';' .
                $request->input('cardPan', '') . ';' .
                $request->input('transactionStatus', '') . ';' .
                $request->input('reasonCode', '');

            $expectedSignature = hash_hmac('md5', $signatureString, $merchantSecret);
            $receivedSignature = $request->input('merchantSignature');

            Log::info('Callback signature verification', [
                'order_reference' => $orderReference,
                'expected' => $expectedSignature,
                'received' => $receivedSignature
            ]);

            if ($expectedSignature === $receivedSignature) {
                $order = Order::findByReference($orderReference);
                if ($order && $request->input('transactionStatus') === 'Approved') {
                    $order->updatePaymentStatus('paid', $request->all());
                    Log::info('Order payment status updated via callback', [
                        'order_reference' => $orderReference
                    ]);
                }

                return response()->json([
                    'orderReference' => $orderReference,
                    'status' => 'accept',
                    'time' => time()
                ]);
            } else {
                Log::error('Invalid callback signature', [
                    'order_reference' => $orderReference,
                    'expected' => $expectedSignature,
                    'received' => $receivedSignature
                ]);

                return response()->json([
                    'orderReference' => $orderReference,
                    'status' => 'decline',
                    'time' => time()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment callback error', [
                'message' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'orderReference' => $request->input('orderReference', ''),
                'status' => 'decline',
                'time' => time()
            ]);
        }
    }

    public function checkStatus()
    {
        $senderStatus = $this->novaPostService->checkSenderSetup();
        $apiTest = $this->novaPostService->testApiKey();

        return response()->json([
            'api_key' => $apiTest,
            'sender_setup' => $senderStatus
        ]);
    }

    public function testApi()
    {
        $result = $this->novaPostService->testApiKey();

        return response()->json($result);
    }

    public function setupSender(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'city' => 'required|string|max:255',
        ]);

        $result = $this->novaPostService->setupSender($validated);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Відправник налаштований успішно' : 'Помилка налаштування відправника'
        ]);
    }
}
