<?php

namespace Molitor\Shop\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Molitor\Product\Models\ProductCategory;
use Molitor\Shop\Http\Livewire\CartComponent;
use Molitor\Shop\Http\Livewire\ProductsListComponent;
use Molitor\Shop\Repositories\CartProductRepository;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class ShopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'shop');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register Livewire components (package namespace)
        // Alias matches Livewire's auto-generated slug for FQCN-based mounting
        Livewire::component('molitor.shop.http.livewire.cart-component', CartComponent::class);
        Livewire::component('molitor.shop.http.livewire.products-list-component', ProductsListComponent::class);

        // Share product categories with the shop layout (left sidebar)
        View::composer('shop::layouts.shop', function ($view) {
            $categories = ProductCategory::query()
                ->whereNull('parent_id')
                ->orderBy('left_value')
                ->get();

            $view->with('shopCategories', $categories);

            // Share cart item count
            /** @var CartProductRepositoryInterface $cart */
            $cart = app(CartProductRepositoryInterface::class);
            $userId = auth()->check() ? (int)auth()->id() : null;
            $sessionId = session()->getId();
            $cartCount = $cart->count($userId, $sessionId);
            $view->with('cartCount', $cartCount);
        });
    }

    public function register()
    {
        $this->app->bind(CartProductRepositoryInterface::class, CartProductRepository::class);
    }
}
