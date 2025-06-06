<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'area_ref',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Область до якої належить район
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_ref', 'ref');
    }

    /**
     * Отримати район за ref
     */
    public static function findByRef(string $ref): ?self
    {
        return static::where('ref', $ref)->first();
    }
}