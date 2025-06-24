<?php


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NovaPostController;


// WayForPay колбек
Route::post('/orders/payment/callback', [NovaPostController::class, 'paymentCallback'])
    ->name('orders.payment.callback');

// Статус замовлення
Route::get('/orders/status/{orderReference}', [NovaPostController::class, 'getOrderStatus'])
    ->name('orders.status');

// Тестові роути
Route::post('/test/callback', function(Request $request) {
    Log::info('Test callback received', [
        'data' => $request->all(),
        'headers' => $request->headers->all(),
        'ip' => $request->ip()
    ]);

    return response()->json([
        'status' => 'received',
        'data' => $request->all()
    ]);
});

Route::get('/test/callback', function() {
    return response()->json([
        'status' => 'callback endpoint is working',
        'time' => now()
    ]);
});
