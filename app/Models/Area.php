<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'description',
        'areas_center',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'area_ref', 'ref');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'recipient_city_ref', 'ref');
    }

    /**
     * Отримати область за ref
     */
    public static function findByRef(string $ref): ?self
    {
        return static::where('ref', $ref)->first();
    }
}
