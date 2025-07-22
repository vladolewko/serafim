<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NovaPoshtaSettlement extends Model
{
    protected $table = 'nova_poshta_settlements';

    protected $fillable = [
        'ref',
        'description',
        'description_ru',
        'settlement_type',
        'settlement_type_description',
        'area_description',
        'area_description_ru',
        'region_description',
        'region_description_ru',
        'delivery',
        'is_city_available',
        'conglomerates',
        'api_warehouses_count',
        'is_active',
    ];

    protected $casts = [
        'delivery' => 'boolean',
        'is_city_available' => 'boolean',
        'is_active' => 'boolean',
        'conglomerates' => 'integer',
        'api_warehouses_count' => 'integer',
    ];

    // Relationships
    public function warehouses()
    {
        return $this->hasMany(NovaPoshtaWarehouse::class, 'settlement_ref', 'ref');
    }

}
