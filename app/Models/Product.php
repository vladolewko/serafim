<?php

namespace App\Models;

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
        'content',
        'for_whom',
    ];
    protected $casts = [
        'for_whom' => 'array',
        'content' => 'array',
    ];
}
