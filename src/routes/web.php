<?php

use Illuminate\Support\Facades\Route;
use Molitor\Shop\Http\Controllers\ShopProductController;
use Molitor\Shop\Http\Controllers\CartController;
use Molitor\Shop\Http\Controllers\ShopAuthController;
use Molitor\Shop\Http\Controllers\ShopProfileController;
use Molitor\Shop\Http\Controllers\ShopCheckoutController;
use Molitor\Shop\Http\Controllers\ShopShippingController;
use Molitor\Shop\Http\Controllers\ShopPaymentController;
use Molitor\Shop\Http\Controllers\ShopInvoiceController;
use Molitor\Shop\Http\Controllers\ShopOrderController;
use Molitor\Shop\Http\Controllers\ShopCategoryController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::middleware('web')->group(function () {
    Route::get('/shop/products', [ShopProductController::class, 'index'])->name('shop.products.index');
    Route::get('/shop/products/{product:slug}', [ShopProductController::class, 'show'])->name('shop.products.show');
    Route::get('/shop/categories/{productCategory:slug}', [ShopCategoryController::class, 'show'])->name('shop.categories.show');

    // Auth
    Route::get('/shop/login', [ShopAuthController::class, 'showLogin'])->name('shop.login');
    Route::post('/shop/login', [ShopAuthController::class, 'login'])->name('shop.login.post');
    Route::get('/shop/register', [ShopAuthController::class, 'showRegister'])->name('shop.register');
    Route::post('/shop/register', [ShopAuthController::class, 'register'])->name('shop.register.post');
    Route::get('/shop/register/success', [ShopAuthController::class, 'registerSuccess'])->name('shop.register.success');
    Route::post('/shop/logout', [ShopAuthController::class, 'logout'])->name('shop.logout');

    // Email verification
    Route::get('/email/verify/{id}/{hash}', [ShopAuthController::class, 'verifyEmail'])
        ->middleware(['web', 'signed'])
        ->name('verification.verify');

    // Profile (auth required)
    Route::middleware('auth')->group(function () {
        Route::get('/shop/profile', [ShopProfileController::class, 'show'])->name('shop.profile.show');
        Route::post('/shop/profile', [ShopProfileController::class, 'update'])->name('shop.profile.update');

        // Checkout wizard
        Route::get('/shop/checkout/shipping', [ShopShippingController::class, 'index'])->name('shop.checkout.shipping');
        Route::get('/shop/checkout/shipping/{shipping:code}', [ShopShippingController::class, 'show'])->name('shop.checkout.shipping.show');
        Route::post('/shop/checkout/shipping/{shipping:code}', [ShopShippingController::class, 'store'])->name('shop.checkout.shipping.store');
        Route::get('/shop/checkout/payment', [ShopPaymentController::class, 'show'])->name('shop.checkout.payment');
        Route::post('/shop/checkout/payment', [ShopPaymentController::class, 'store'])->name('shop.checkout.payment.store');
        Route::get('/shop/checkout/invoice', [ShopInvoiceController::class, 'show'])->name('shop.checkout.invoice');
        Route::post('/shop/checkout/invoice', [ShopInvoiceController::class, 'store'])->name('shop.checkout.invoice.store');
        Route::get('/shop/checkout/finalize', [ShopCheckoutController::class, 'showFinalize'])->name('shop.checkout.finalize');
        Route::post('/shop/checkout/place', [ShopCheckoutController::class, 'placeOrder'])->name('shop.checkout.place');

        // Backward compatible single-page endpoint (optional)
        Route::get('/shop/checkout', [ShopCheckoutController::class, 'redirectToWizard'])->name('shop.checkout.show');
        Route::post('/shop/checkout', [ShopCheckoutController::class, 'store'])->name('shop.checkout.store');

        // Orders
        Route::get('/shop/orders', [ShopOrderController::class, 'index'])->name('shop.orders.index');
        Route::get('/shop/orders/{code}', [ShopOrderController::class, 'show'])->name('shop.orders.show');
    });

    // Cart
    Route::get('/shop/cart', [CartController::class, 'index'])->name('shop.cart.index');
    Route::post('/shop/cart', [CartController::class, 'store'])->name('shop.cart.store');
});
