<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'total_amount',
        'payment_method',
        'status',
        'recipient_first_name',
        'recipient_last_name',
        'recipient_middle_name',
        'recipient_phone',
        'recipient_email',
        'recipient_city_ref',
        'recipient_warehouse_ref',
        'weight',
        'seats_amount',
        'description',
        'ttn_number',
        'ttn_ref',
        'shipping_cost',
        'notes',
        'shipped_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'weight' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'seats_amount' => 'integer',
        'shipped_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Статуси замовлення
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Способи оплати
     */
    const PAYMENT_CASH_ON_DELIVERY = 'cash_on_delivery';
    const PAYMENT_CARD = 'card';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';

    /**
     * Отримати повне ім'я отримувача
     */
    public function getRecipientFullNameAttribute(): string
    {
        return trim($this->recipient_last_name . ' ' . $this->recipient_first_name . ' ' . $this->recipient_middle_name);
    }

    /**
     * Чи є замовлення відправленим
     */
    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    /**
     * Чи є замовлення доставленим
     */
    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Чи можна створити ТТН для замовлення
     */
    public function canCreateTTN(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]) 
               && empty($this->ttn_number);
    }

    /**
     * Позначити замовлення як відправлене
     */
    public function markAsShipped(string $ttnNumber, string $ttnRef = null): void
    {
        $this->update([
            'status' => self::STATUS_SHIPPED,
            'ttn_number' => $ttnNumber,
            'ttn_ref' => $ttnRef,
            'shipped_at' => Carbon::now(),
        ]);
    }

    /**
     * Скоуп для неопрацьованих замовлень
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Скоуп для відправлених замовлень
     */
    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    /**
     * Скоуп для замовлень з ТТН
     */
    public function scopeWithTTN($query)
    {
        return $query->whereNotNull('ttn_number');
    }
}