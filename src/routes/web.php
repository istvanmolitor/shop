<?php

use Illuminate\Support\Facades\Route;
use Molitor\Shop\Http\Controllers\ShopProductController;

Route::get('/shop/products', [ShopProductController::class, 'index'])->name('shop.products.index');
Route::get('/shop/products/{product:slug}', [ShopProductController::class, 'show'])->name('shop.products.show');
