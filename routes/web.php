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


// Роути для замовлень
Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'create'])->name('orders.create');

    Route::post('/setArea', [OrderController::class, 'setArea'])->name('orders.setArea');
    Route::post('/setDistrict', [OrderController::class, 'setDistrict'])->name('orders.setDistrict');
    Route::post('/setSettlement', [OrderController::class, 'setSettlement'])->name('orders.setSettlement');
    Route::post('/searchSettlement', [OrderController::class, 'searchSettlement'])->name('orders.searchSettlement');
    Route::post('/chooseSettlement', [OrderController::class, 'chooseSettlement'])->name('orders.chooseSettlement');
    Route::post('/setWarehouse', [OrderController::class, 'setWarehouse'])->name('orders.setWarehouse');
    Route::post('/createCounterparty', [OrderController::class, 'createCounterparty'])->name('orders.createCounterparty');
});


Route::get('setCitySender', [OrderController::class, 'setCitySender']);
Route::get('setSenderRef', [OrderController::class, 'setSenderRef']);

Route::post('/setup-sender', [OrderController::class, 'setupSender'])->name('orders.setupSender');
Route::get('/checkStatus', [OrderController::class, 'checkStatus'])->name('orders.checkStatus');

Route::get('/test/create-ttn/{orderReference}', [OrderController::class, 'createTTNManually']);



