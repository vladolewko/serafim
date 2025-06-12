<?php

namespace App\Http\Controllers;

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

            // Створюємо контрагента
            $counterparty = $this->novaPostService->createCounterparty($validated);

            if($payment == 'cash') {
                // Створюємо ТТН
                $ttn = $this->novaPostService->createTTN([
                    'settlement' => $data['settlement'],
                    'warehouse' => $data['warehouse'],
                    'counterparty_ref' => $counterparty['Ref'],
                    'phone' => $validated['phone'],
                    'name' => $validated['name'],
                    'surname' => $validated['surname'],
                ], $cart, $payment);

                Session::forget('nova_post_data');
                Session::forget('cart');

                return response()->json([
                    'success' => true,
                    'ttn_number' => $ttn['IntDocNumber'] ?? $ttn['Number'] ?? 'Невідомий номер',
                    'message' => 'ТТН успішно створено'
                ], 200, [], JSON_UNESCAPED_UNICODE);
            } elseif ($payment == 'card') {
                // Зберігаємо дані замовлення в сесії для подальшого використання після оплати
                Session::put('pending_order', [
                    'settlement' => $data['settlement'],
                    'warehouse' => $data['warehouse'],
                    'counterparty_ref' => $counterparty['Ref'],
                    'phone' => $validated['phone'],
                    'name' => $validated['name'],
                    'surname' => $validated['surname'],
                    'email' => $request->input('email'),
                    'cart' => $cart
                ]);

                // Генеруємо дані для WayForPay
                $wayForPayData = $this->generateWayForPayData($validated, $cart);

                return response()->json([
                    'success' => true,
                    'payment_type' => 'card',
                    'wayforpay_data' => $wayForPayData
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка валідації: ' . implode(', ', $e->validator->errors()->all())
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating TTN in controller', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function generateWayForPayData($data, $cart)
    {
        $orderReference = 'ORDER_' . time() . '_' . rand(1000, 9999);
        $amount = $cart['total'] + 60; // Додаємо вартість доставки
        $merchantAccount = 'test_merch_n1'; // Тестовий акаунт
        $merchantSecret = 'flk3409refn54t54t*FNJRET'; // Тестовий секрет

        // Створюємо підпис
        $signatureString = $merchantAccount . ';' . $data['name'] . ';' . $orderReference . ';' . $amount . ';UAH;' . $data['email'];
        $signature = hash_hmac('md5', $signatureString, $merchantSecret);

        return [
            'merchantAccount' => $merchantAccount,
            'merchantAuthType' => 'SimpleSignature',
            'merchantDomainName' => request()->getHost(),
            'merchantSignature' => $signature,
            'orderReference' => $orderReference,
            'orderDate' => time(),
            'amount' => $amount,
            'currency' => 'UAH',
            'productName' => ['Замовлення з інтернет-магазину'],
            'productPrice' => [$amount],
            'productCount' => [1],
            'clientFirstName' => $data['name'],
            'clientLastName' => $data['surname'],
            'clientEmail' => $data['email'],
            'defaultPaymentSystem' => 'card',
            'returnUrl' => route('payment.success'),
            'serviceUrl' => route('payment.success')
        ];
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
