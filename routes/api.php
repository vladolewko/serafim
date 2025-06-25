<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/orders/payment/callback', [OrderController::class, 'paymentCallback'])
    ->withoutMiddleware(['auth', 'verified'])
    ->name('orders.payment.callback');

Route::get('/orders/status/{orderReference}', [OrderController::class, 'getOrderStatus'])
    ->name('orders.status');

