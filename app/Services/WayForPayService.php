<?php

namespace App\Services;
use Illuminate\Http\Request;

class WayForPayService
{
    private $merchantAccount;
    private $merchantSecretKey;
    private $merchantDomainName;

    public function __construct()
    {
        $this->merchantAccount = config('services.wayforpay.merchant_account');
        $this->merchantSecretKey = config('services.wayforpay.secret_key');
        $this->merchantDomainName = config('services.wayforpay.url', 'https://serafym.info');

        if (!$this->merchantAccount || !$this->merchantSecretKey) {
            throw new \Exception('WayForPay не налаштований');
        }
    }

    /**
     * Підготовка даних для оплати
     */
    public function preparePaymentData($validated, $cart, $data, $orderReference)
    {
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
            $this->merchantAccount,
            $this->merchantDomainName,
            $orderReference,
            $orderDate,
            $amount,
            $currency,
            implode(';', $productName),
            implode(';', $productCount),
            implode(';', $productPrice)
        ]);

        $merchantSignature = hash_hmac('md5', $signString, $this->merchantSecretKey);
        $callbackUrl = rtrim($this->merchantDomainName, '/') . '/api/orders/payment/callback';

        return [
            'merchantAccount' => $this->merchantAccount,
            'merchantDomainName' => $this->merchantDomainName,
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
            'serviceUrl' => $callbackUrl,
            'merchantSignature' => $merchantSignature
        ];
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
