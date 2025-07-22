<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NovaPoshtaWarehouse extends Model
{
    protected $table = 'nova_poshta_warehouses';

    protected $fillable = [
        'ref',
        'description',
        'description_ru',
        'short_address',
        'short_address_ru',
        'phone',
        'type_of_warehouse',
        'warehouse_type',
        'category_of_warehouse',
        'total_max_weight_allowed',
        'max_volume_allowed',
        'place_max_weight_allowed',
        'dimensions_allowed',
        'settlement_ref',
        'city_ref',
        'city_description',
        'city_description_ru',
        'longitude',
        'latitude',
        'post_finance',
        'bicycle_parking',
        'payment_access',
        'pos_terminal',
        'international_shipping',
        'self_service_workplaces_count',
        'total_max_weight_allowed_details',
        'work_in_mobile_awis',
        'direct_direction',
        'return_direction',
        'reception',
        'delivery',
        'schedule',
        'district_code',
        'warehouse_status',
        'warehouse_status_date',
        'warehouse_illiquid_status',
        'warehouse_illiquid_status_date',
        'generator_enabled',
        'mail_only',
        'copy_work_hours',
        'services_filter',
        'type_of_restrictions',
        'is_active',
    ];

    protected $casts = [
        'total_max_weight_allowed' => 'decimal:2',
        'max_volume_allowed' => 'decimal:2',
        'place_max_weight_allowed' => 'integer',
        'longitude' => 'decimal:7',
        'latitude' => 'decimal:7',
        'generator_enabled' => 'integer',
        'mail_only' => 'integer',
        'is_active' => 'boolean',
        'dimensions_allowed' => 'array',
        'post_finance' => 'array',
        'bicycle_parking' => 'array',
        'payment_access' => 'array',
        'pos_terminal' => 'array',
        'international_shipping' => 'array',
        'self_service_workplaces_count' => 'array',
        'total_max_weight_allowed_details' => 'array',
        'direct_direction' => 'array',
        'return_direction' => 'array',
        'reception' => 'array',
        'delivery' => 'array',
        'schedule' => 'array',
        'copy_work_hours' => 'array',
        'services_filter' => 'array',
        'type_of_restrictions' => 'array',
    ];

    // Relationships
    public function settlement()
    {
        return $this->belongsTo(NovaPoshtaSettlement::class, 'settlement_ref', 'ref');
    }


}
