<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\NovaPostService;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\OrderService;
use App\Services\WayForPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class OrderController extends Controller
{
    protected $novaPostService;
    protected $productService;
    protected $wayForPayService;
    protected $orderService;

    public function __construct(
        NovaPostService $novaPostService,
        ProductServiceInterface $productService,
        WayForPayService $wayForPayService,
        OrderService $orderService
    ) {
        $this->novaPostService = $novaPostService;
        $this->productService = $productService;
        $this->wayForPayService = $wayForPayService;
        $this->orderService = $orderService;
    }

    /**
     * Показати форму створення замовлення
     */
    public function create(Request $request)
    {
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
        $product = $this->productService->getById($productId);

        if ($product && $quantity && $quantity > 0) {
            session()->put('cart', [
                'product' => $product,
                'quantity' => $quantity,
//                'total' => $product->price * $quantity
                'total' => 5
            ]);
            $cart = session()->get('cart');

            return view('site.orders.create', compact('cart'));
        }

        return back()->with('error', 'Помилка');
    }

    /**
     * Пошук населених пунктів
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
            return response()->json([
                'success' => false,
                'error' => 'Сталася помилка при пошуку населених пунктів. Спробуйте пізніше.'
            ], 500);
        }
    }

    /**
     * Вибір населеного пункту
     */
    public function chooseSettlement(Request $request)
    {
        try {
            $validated = $request->validate([
                'settlement' => [
                    'required',
                    'string',
                    'size:36'
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
            return response()->json([
                'success' => false,
                'error' => 'Помилка при завантаженні відділень. Спробуйте оновити сторінку.'
            ], 500);
        }
    }

    /**
     * Вибір відділення
     */
    public function setWarehouse(Request $request)
    {
        try {
            $validated = $request->validate([
                'warehouse' => [
                    'required',
                    'string',
                    'size:36'
                ]
            ], [
                'warehouse.required' => 'Оберіть відділення або поштомат',
                'warehouse.size' => 'Некоректний ідентифікатор відділення'
            ]);

            $warehouseRef = $validated['warehouse'];
            $data = Session::get('nova_post_data', []);

            if (empty($data['settlement']) || empty($data['search'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Втрачено дані про населений пункт. Почніть процес заново.'
                ], 400);
            }

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

            $deliveryCost = $this->orderService->calculateDeliveryCost($cart, $data['settlement']);

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
            return response()->json([
                'success' => false,
                'error' => 'Помилка при розрахунку доставки. Спробуйте ще раз.'
            ], 500);
        }
    }

    /**
     * Створення замовлення
     */
    public function createCounterparty(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'],
                'surname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[а-яА-ЯіІїЇєЄ\s\-\']+$/u'],
                'phone' => ['required', 'string'],
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

            $result = $this->orderService->processOrder($validated, $cart, $data);

            return response()->json($result);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка валідації: ' . $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Сталася помилка при створенні замовлення'
            ], 500);
        }
    }

    /**
     * Callback для оплати
     */
    public function paymentCallback(Request $request)
    {
        try {
            $data = $this->wayForPayService->parseCallbackData($request);

            if (!$this->wayForPayService->verifySignature($data)) {
                return response('Invalid signature', 400);
            }

            $result = $this->orderService->handlePaymentCallback($data);

            return response('OK', 200);

        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Статус замовлення
     */
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
            return response()->json([
                'success' => false,
                'message' => 'Помилка при отриманні статусу замовлення'
            ], 500);
        }
    }

    /**
     * Налаштування відправника
     */
    public function setupSender(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:50'],
                'surname' => ['required', 'string', 'min:2', 'max:50'],
                'phone' => ['required', 'string'],
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
            return response()->json([
                'success' => false,
                'message' => 'Помилка при налаштуванні відправника'
            ], 500);
        }
    }

    /**
     * Перевірка статусу сервісу
     */
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
            return response()->json([
                'api_key' => false,
                'sender_setup' => false,
                'status' => 'error',
                'message' => 'Помилка перевірки статусу сервісу'
            ], 500);
        }
    }
}
