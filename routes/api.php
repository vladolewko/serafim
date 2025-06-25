<?php

// 1. ПЕРЕВІРКА РОУТІВ - api.php
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\NovaPostController;

// Важливо! Додайте middleware для CSRF виключення
Route::post('/orders/payment/callback', [OrderController::class, 'paymentCallback'])
    ->withoutMiddleware(['auth', 'verified']) // Виключаємо auth middleware
    ->name('orders.payment.callback');

Route::get('/orders/status/{orderReference}', [OrderController::class, 'getOrderStatus'])
    ->name('orders.status');

// Тестові роути для діагностики
Route::post('/test/callback', function(Request $request) {
    Log::info('Test callback received', [
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'data' => $request->all(),
        'headers' => $request->headers->all(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);

    return response()->json([
        'status' => 'received',
        'timestamp' => now(),
        'data' => $request->all()
    ]);
})->withoutMiddleware(['auth', 'verified']);

Route::get('/test/callback', function() {
    return response()->json([
        'status' => 'GET endpoint working',
        'time' => now(),
        'url' => request()->fullUrl()
    ]);
});
