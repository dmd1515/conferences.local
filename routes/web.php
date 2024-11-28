<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;
Route::get('/', function () {
    return view('e-shop.shop');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create');
Route::post('/store', [ShopController::class, 'store'])->name('store');