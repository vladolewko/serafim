<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchSettlementRequest;
use App\Http\Requests\ChooseSettlementRequest;
use App\Http\Requests\SetWarehouseRequest;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Services\KeyCrmService;
use App\Services\NovaPostService;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\OrderService;
use App\Services\WayForPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $novaPostService;
    protected $productService;
    protected $wayForPayService;
    protected $orderService;
    protected $keyCrmService;
    public function __construct(
        NovaPostService $novaPostService,
        ProductServiceInterface $productService,
        WayForPayService $wayForPayService,
        OrderService $orderService,
        KeyCrmService $keyCrmService,
    ) {
        $this->novaPostService = $novaPostService;
        $this->productService = $productService;
        $this->wayForPayService = $wayForPayService;
        $this->orderService = $orderService;
        $this->keyCrmService = $keyCrmService;
    }

    /**
     * Show product creation form
     */
    public function create(Request $request)
    {
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
        $product = $this->productService->getById($productId);

        if ($product && $quantity && $quantity > 0) {
            session()->put('cart', [
                'productId' => $productId,
                'quantity' => $quantity,
                'total' => $product->price * $quantity
//                'total' => 5
            ]);
            $cart = session()->get('cart');

            return view('site.order', compact('cart'));
        }

        return back()->with('error', 'Помилка');
    }

    /**
     * Searching settlements by search
     */
    public function searchSettlement(SearchSettlementRequest $request): JsonResponse
    {
        try {
            $search = trim($request->validated()['search']);
            $settlements = $this->novaPostService->searchSettlement($search);

            if (empty($settlements)) {
                return $this->errorResponse(
                    'Населені пункти з такою назвою не знайдено. Спробуйте ввести частину назви або перевірте правильність написання.',
                    [
                        'suggestions' => 'Спробуйте ввести: "Київ", "Львів", "Одеса" або частину назви вашого міста'
                    ]
                );
            }

            Session::put('nova_post_data', ['search' => $search]);

            return $this->successResponse([
                'settlements' => $settlements,
                'addressData' => ['search' => $search],
                'count' => count($settlements)
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Сталася помилка при пошуку населених пунктів. Спробуйте пізніше.',
                [],
                500
            );
        }
    }

    /**
     * Saving Settlement to Order Data
     */
    public function chooseSettlement(ChooseSettlementRequest $request): JsonResponse
    {
        try {
            $settlementRef = $request->validated()['settlement'];
            $data = Session::get('nova_post_data', []);

            if (empty($data['search'])) {
                return $this->errorResponse(
                    'Спочатку виконайте пошук населеного пункту',
                    [],
                    400
                );
            }

            $data['settlement'] = $settlementRef;
            Session::put('nova_post_data', $data);

            $cart = session('cart');
            $warehouses = $this->novaPostService->getFilteredWarehouses($settlementRef, $cart);
            $settlements = $this->novaPostService->searchSettlement($data['search']);

            if (empty($warehouses)) {
                $cart = session('cart');
                $quantity = $cart['quantity'] ?? 1;

                if ($quantity > 1) {
                    return $this->errorResponse(
                        "Для {$quantity} товарів доступні тільки відділення (не поштомати). У цьому населеному пункті підходящих відділень не знайдено.",
                        ['suggestion' => 'Спробуйте обрати інше місто або зменшіть кількість до 1']
                    );
                }

                return $this->errorResponse(
                    'У цьому населеному пункті немає відділень для вашого товару',
                    ['suggestion' => 'Спробуйте обрати інше місто']
                );
            }

            return $this->successResponse([
                'warehouses' => $warehouses,
                'settlements' => $settlements,
                'addressData' => $data,
                'warehouses_count' => count($warehouses)
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Помилка при завантаженні відділень. Спробуйте оновити сторінку.',
                [],
                500
            );
        }
    }

    /**
     * Saving Warehouse to session
     */
    public function setWarehouse(SetWarehouseRequest $request): JsonResponse
    {
        try {
            $warehouseRef = $request->validated()['warehouse'];
            $data = Session::get('nova_post_data', []);

            if (empty($data['settlement']) || empty($data['search'])) {
                return $this->errorResponse(
                    'Втрачено дані про населений пункт. Почніть процес заново.',
                    [],
                    400
                );
            }

            $cart = session('cart');
            if (empty($cart) || !$cart['productId'] || empty($cart['quantity'])) {
                return $this->errorResponse(
                    'Корзина порожня. Додайте товари перед оформленням замовлення.',
                    ['redirect' => route('home')],
                    400
                );
            }

            $data['warehouse'] = $warehouseRef;

            $settlements = $this->novaPostService->searchSettlement($data['search']);
            $warehouses = $this->novaPostService->getFilteredWarehouses($data['settlement'], $cart);

            $deliveryCost = $this->orderService->calculateDeliveryCost($cart, $data['settlement']);
            $data['deliveryCost'] = $deliveryCost;
            Session::put('nova_post_data', $data);

            if ($deliveryCost < 0) {
                return $this->errorResponse(
                    'Не вдалося розрахувати вартість доставки. Спробуйте обрати інше відділення.',
                    [],
                    400
                );
            }

            return $this->successResponse([
                'deliveryCost' => $deliveryCost,
                'productCosts' => $cart['total'],
                'totalAmount' => $cart['total'] + $deliveryCost,
                'addressData' => $data,
                'settlements' => $settlements,
                'warehouses' => $warehouses
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Помилка при розрахунку доставки. Спробуйте ще раз.',
                [],
                500
            );
        }
    }

    /**
     * Trying to create order
     */
    public function createOrder(CreateOrderRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $cart = session()->get('cart');
            $data = Session::get('nova_post_data', []);

            // Валідація корзини
            if (empty($cart)) {
                return $this->errorResponse('Корзина порожня', [], 400);
            }

            if (!isset($cart['productId']) || !isset($cart['quantity']) || !isset($cart['total'])) {
                return $this->errorResponse('Некоректні дані корзини', [], 400);
            }

            if ($cart['quantity'] <= 0 || $cart['total'] <= 0) {
                return $this->errorResponse('Некоректна кількість або сума товарів', [], 400);
            }

            // Валідація даних доставки
            if (empty($data['settlement']) || empty($data['warehouse'])) {
                return $this->errorResponse('Не обрано адресу доставки', [], 400);
            }

            // Додаткова валідація для карткових платежів
            if ($validated['payment'] === 'card') {
                // Перевірка обов'язкових полів для онлайн оплати
                if (empty($validated['email'])) {
                    return $this->errorResponse('Email обов\'язковий для онлайн оплати', [], 400);
                }

                if (!filter_var($validated['email'], FILTER_VALIDATE_EMAIL)) {
                    return $this->errorResponse('Некоректний email', [], 400);
                }
            }

            // Обробка замовлення
            $result = $this->orderService->processOrder($validated, $cart, $data);

            if ($result['success']) {
                // Для карткових платежів додаємо додаткову інформацію
                if ($validated['payment'] === 'card' && isset($result['wayforpay_data'])) {
                    $responseData = [
                        'message' => 'Замовлення створено, перенаправлення на оплату',
                        'payment_type' => 'card',
                        'wayforpay_data' => $result['wayforpay_data'],
                        'order_reference' => $result['order_reference'] ?? null
                    ];

                    return $this->successResponse($responseData);
                }

                return $this->successResponse($result);
            } else {
                Log::warning('Order creation failed', [
                    'validated' => $validated,
                    'cart' => $cart,
                    'data' => $data,
                    'result' => $result
                ]);

                return $this->errorResponse($result['message'], $result);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Помилка валідації', $e->errors(), 422);

        } catch (\Exception $e) {
            Log::error('Order creation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'validated' => $validated ?? null,
                'cart' => $cart ?? null,
                'data' => $data ?? null
            ]);

            return $this->errorResponse(
                'Сталася помилка при створенні замовлення: ' . $e->getMessage(),
                [],
                500
            );
        }
    }

    /**
     * Callback for payment
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
     * Success response pattern
     */
    private function successResponse(array $data = [], int $status = 200): JsonResponse
    {
        return response()->json(array_merge([
            'success' => true,
            'timestamp' => now()->toISOString()
        ], $data), $status);
    }

    /**
     * error response pattern
     */
    private function errorResponse(string $message, array $data = [], int $status = 400): JsonResponse
    {
        return response()->json(array_merge([
            'success' => false,
            'error' => $message,
            'timestamp' => now()->toISOString()
        ], $data), $status);
    }


    /**
     * Getting Order Status
     */
    public function getOrderStatus($orderReference): JsonResponse
    {
        try {
            $order = Order::where('order_reference', $orderReference)->first();

            if (!$order) {
                return $this->errorResponse(
                    'Замовлення не знайдено',
                    [],
                    404
                );
            }

            return $this->successResponse([
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
            return $this->errorResponse(
                'Помилка при отриманні статусу замовлення',
                [],
                500
            );
        }
    }
}
