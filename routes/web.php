<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Services\ProductService;


Route::get('/', [ProductController::class, 'getAll'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'getById'])->name('product.show');
