<?php

use Illuminate\Support\Facades\Route;
use Molitor\Shop\Http\Controllers\ShopProductController;
use Molitor\Shop\Http\Controllers\CartController;

Route::middleware('web')->group(function () {
    Route::get('/shop/products', [ShopProductController::class, 'index'])->name('shop.products.index');
    Route::get('/shop/products/{product:slug}', [ShopProductController::class, 'show'])->name('shop.products.show');

    // Cart
    Route::get('/shop/cart', [CartController::class, 'index'])->name('shop.cart.index');
    Route::post('/shop/cart', [CartController::class, 'store'])->name('shop.cart.store');
});
