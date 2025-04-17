<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');


Auth::routes();

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
//Route::post('register', [RegisterController::class, 'register'])->name('register');
/*Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create');
});*/
Route::post('/store', [ShopController::class, 'store'])->name('store');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('cart/apply-promo', [CartController::class, 'applyPromo'])->name('cart.applyPromo');
Route::post('/cart/remove-promo', [CartController::class, 'removePromo'])->name('cart.removePromo');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::post('/payment/success', [ShopController::class, 'paymentSuccess'])->name('payment.success');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create');
    Route::get('/product/edit/{id}', [ShopController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [ShopController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [ShopController::class, 'destroy'])->name('product.destroy');
});




