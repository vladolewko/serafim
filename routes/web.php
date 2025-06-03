<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Services\ProductService;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminMiddleware;


Route::get('/', [ProductController::class, 'getAll'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'getById'])->name('product.show');


Route::get('/admin', function () {
    return view('admin.login');
})->name('admin.login');
Route::post('/admin/signin', [AdminController::class, 'signIn'])->name('admin.signIn');


Route::middleware(AdminMiddleware::class)->group(function () {
Route::get('/admin/logout', [AdminController::class, 'logOut'])->name('admin.logout');
Route::get('/admin/products', [AdminProductController::class, 'products'])->name('admin.products');
Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
Route::put('/admin/products/store', [AdminProductController::class, 'store'])->name('admin.products.store');
Route::get('/admin/product/{id}', [AdminProductController::class, 'edit'])->name('admin.products.edit');
});

