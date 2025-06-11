<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Services\ProductService;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NovaPostController;
use App\Http\Controllers\SenderSetupController;


Route::get('/', [ProductController::class, 'getAll'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'getById'])->name('product.show');


Route::get('/admin', function () {
    return view('admin.login');
})->name('admin.login');
Route::post('/admin/signin', [AdminController::class, 'signIn'])->name('admin.signIn');


Route::middleware(AdminMiddleware::class)->group(function () {
    Route::get('/admin/logout', [AdminController::class, 'logOut'])->name('admin.logout');
    Route::get('/admin/novaPostSetup', [AdminController::class, 'novaPostSetup'])->name('admin.novaPostSetup');
    Route::get('/admin/products', [AdminProductController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::put('/admin/products/store', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::patch('/admin/products/update', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/destroy/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/admin/product/{id}', [AdminProductController::class, 'edit'])->name('admin.products.edit');
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Роути для замовлень
Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'create'])->name('orders.create');

    // Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/setArea', [NovaPostController::class, 'setArea'])->name('orders.setArea');
    Route::post('/setDistrict', [NovaPostController::class, 'setDistrict'])->name('orders.setDistrict');
    Route::post('/setSettlement', [NovaPostController::class, 'setSettlement'])->name('orders.setSettlement');
    Route::post('/searchSettlement', [NovaPostController::class, 'searchSettlement'])->name('orders.searchSettlement');
    Route::post('/chooseSettlement', [NovaPostController::class, 'chooseSettlement'])->name('orders.chooseSettlement');
    Route::post('/setWarehouse', [NovaPostController::class, 'setWarehouse'])->name('orders.setWarehouse');
    Route::post('/createCounterparty', [NovaPostController::class, 'createCounterparty'])->name('orders.createCounterparty');
});

// Роути для Nova Post
Route::prefix('nova-post')->group(function () {
    Route::post('/calculate-shipping', [OrderController::class, 'calculateShipping']);
    Route::get('/areas', [NovaPostController::class, 'getAreas']);
    Route::get('/areas/{areaRef}/districts', [NovaPostController::class, 'getDistricts']);
    Route::get('/settlements/{areaRef}', [NovaPostController::class, 'getSettlements']);
    Route::get('/warehouses/{settlementRef}', [NovaPostController::class, 'getWarehouses']);
    Route::post('/search-settlements', [NovaPostController::class, 'searchSettlements']);
    Route::post('/update-areas', [NovaPostController::class, 'updateAreas']);
});
    Route::get('/districts', [NovaPostController::class, 'getDistricts']);




Route::get('setCitySender', [NovaPostController::class, 'setCitySender']);
Route::get('setSenderRef', [NovaPostController::class, 'setSenderRef']);

Route::post('/setup-sender', [NovaPostController::class, 'setupSender'])->name('orders.setupSender');
Route::get('/checkStatus', [NovaPostController::class, 'checkStatus'])->name('orders.checkStatus');

Route::get('/wayForPay', function () {
    return view('orders.wayForPay');
})->name('wayForPay');


// У web.php
Route::post('/payment/success', [OrderController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/payment/fail', [OrderController::class, 'paymentFail'])->name('payment.fail');
