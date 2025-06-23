<?php

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Services\ProductService;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NovaPostController;


//Route::get('/orders/payment/return', [NovaPostController::class, 'paymentReturn'])->name('orders.payment.return');
//
//Route::post('/orders/payment/callback', [NovaPostController::class, 'paymentCallback'])->name('orders.payment.callback');
//
//// web.php або api.php
//Route::get('/orders/ttn/status', [OrderController::class, 'checkTTNStatus'])->name('orders.ttn.status');
//
//Route::post('/test-webhook', function(Request $request) {
//    Log::info('🔥 TEST WEBHOOK CALLED!', $request->all());
//    return 'OK';
//});
//
//Route::get('/api/orders/payment/return', [OrderController::class, 'paymentReturn'])->name('orders.payment.return');
//Route::post('/api/orders/payment/callback', [OrderController::class, 'paymentCallback'])->name('orders.payment.callback');
