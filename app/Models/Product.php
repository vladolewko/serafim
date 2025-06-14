<?php

namespace App\Models;

use App\Enums\ProductApplyingEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'price',
        'books_quantity',
        'weight',
        'dimension',
        'appointment',
        'applying',
        'content',
        'for_whom',
    ];
    protected $casts = [
        'applying' => ProductApplyingEnum::class,
        'for_whom' => 'array',
        'content' => 'array',
    ];
    public static function getApplyingOptions(): array
    {
        return collect(ProductApplyingEnum::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
