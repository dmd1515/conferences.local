<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
Route::get('/', [ShopController::class, 'index'])->name('shop.index');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
/*Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create');
});*/
Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create');
Route::post('/store', [ShopController::class, 'store'])->name('store');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

