<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_reference',
        'status',
        'payment_type',
        'payment_status',
        'customer_name',
        'customer_surname',
        'customer_phone',
        'customer_email',
        'settlement_ref',
        'warehouse_ref',
        'counterparty_ref',
        'contact_person_ref',
        'cart_data',
        'product_total',
        'delivery_cost',
        'total_amount',
        'wayforpay_data',
        'ttn_number',
        'ttn_response',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'cart_data' => 'array',
        'wayforpay_data' => 'array',
        'ttn_response' => 'array',
        'product_total' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_date' => 'datetime'
    ];

    /**
     * Генерує унікальний номер замовлення
     */
    public static function generateOrderReference(): string
    {
        do {
            $orderReference = 'ORDER_' . time() . '_' . rand(1000, 9999);
        } while (self::where('order_reference', $orderReference)->exists());

        return $orderReference;
    }

    /**
     * Отримує замовлення за номером
     */
    public static function findByReference(string $orderReference): ?self
    {
        return self::where('order_reference', $orderReference)->first();
    }

    /**
     * Оновлює статус оплати
     */
    public function updatePaymentStatus(string $status, ?array $paymentData = null): void
    {
        $this->payment_status = $status;

        if ($status === 'paid') {
            $this->payment_date = now();
            $this->status = 'paid';
        }

        if ($paymentData) {
            $currentData = $this->wayforpay_data ?? [];
            $this->wayforpay_data = array_merge($currentData, $paymentData);
        }

        $this->save();
    }

    /**
     * Додає дані ТТН
     */
    public function addTTNData($ttnNumber, $ttnData)
    {
        $this->update([
            'ttn_number' => $ttnNumber,
            'ttn_data' => json_encode($ttnData),
            'status' => 'shipped'
        ]);
    }

    /**
     * Перевіряє чи замовлення оплачене
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Перевіряє чи замовлення готове до створення ТТН
     */
    public function isReadyForTTN(): bool
    {
        return $this->payment_type === 'cash' || $this->isPaid();
    }
}
