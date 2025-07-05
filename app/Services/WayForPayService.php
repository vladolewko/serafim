<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WayForPayService
{
    private $merchantAccount;
    private $merchantSecretKey;
    private $merchantDomainName;
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->merchantAccount = config('services.wayforpay.merchant_account');
        $this->merchantSecretKey = config('services.wayforpay.secret_key');
        $this->merchantDomainName = config('services.wayforpay.url', 'https://serafym.info');

        $this->productService = $productService;

        if (!$this->merchantAccount || !$this->merchantSecretKey) {
            throw new \Exception('WayForPay не налаштований');
        }
    }

    /**
     * Підготовка даних для оплати
     */
    public function preparePaymentData($validated, $cart, $data, $orderReference = null)
    {
        try {
            $total = (int)$cart['total'];
            $deliveryCost = $data['deliveryCost'] ?? 0;
            $totalAmount = $total + $deliveryCost;

            $amount = number_format($total, 2, '.', '');
            $currency = 'UAH';
            $orderDate = time();
            $orderRef = $orderReference ?: 'ORDER_' . time() . '_' . rand(1000, 9999);

            // Отримуємо продукт
            $productId = null;
            if (isset($cart['product']['id'])) {
                $productId = $cart['product']['id'];
            } elseif (isset($cart['productId'])) {
                $productId = $cart['productId'];
            }

            if (!$productId) {
                throw new \Exception('Product ID not found in cart');
            }

            $product = $this->productService->getById($productId);
            if (!$product) {
                throw new \Exception('Product not found');
            }

            // Правильно формуємо масиви даних
            $productName = [$product->name]; // ← Масив з назвою продукту
            $productPrice = [number_format($total, 2, '.', '')];
            $productCount = [(string)$cart['quantity']];

            // Формуємо строку для підпису
            $signString = implode(';', [
                $this->merchantAccount,
                $this->merchantDomainName,
                $orderRef,
                $orderDate,
                $amount,
                $currency,
                implode(';', $productName), // ← Тепер це правильно
                implode(';', $productCount),
                implode(';', $productPrice)
            ]);

            $merchantSignature = hash_hmac('md5', $signString, $this->merchantSecretKey);
            $callbackUrl = rtrim($this->merchantDomainName, '/') . '/api/orders/payment/callback';

            return [
                'merchantAccount' => $this->merchantAccount,
                'merchantDomainName' => $this->merchantDomainName,
                'orderReference' => $orderRef,
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
                'serviceUrl' => $callbackUrl,
                'merchantSignature' => $merchantSignature
            ];

        } catch (\Exception $e) {
            Log::error('WayForPay payment data preparation failed', [
                'error' => $e->getMessage(),
                'validated' => $validated,
                'cart' => $cart,
                'data' => $data
            ]);
            return null;
        }
    }

    /**
     * Парсинг даних з callback
     */
    public function parseCallbackData(Request $request)
    {
        $data = [];
        $rawInput = $request->getContent();

        if ($request->has('orderReference')) {
            $data = $request->all();
        } elseif ($request->isJson()) {
            $data = $request->json()->all();
        } elseif (!empty($rawInput)) {
            $jsonData = json_decode($rawInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
                $data = $jsonData;
            } else {
                parse_str($rawInput, $data);
            }
        }

        if (!isset($data['orderReference']) || !isset($data['transactionStatus'])) {
            throw new \Exception('Missing required fields in callback');
        }

        return $data;
    }

    /**
     * Перевірка підпису
     */
    public function verifySignature($data)
    {
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

        $expectedSignature = hash_hmac('md5', $signString, $this->merchantSecretKey);
        return $expectedSignature === ($data['merchantSignature'] ?? '');
    }
}
