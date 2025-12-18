<?php

namespace Molitor\Shop\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Molitor\Shop\Http\Livewire\CartComponent;
use Molitor\Shop\Http\Livewire\HeaderCartComponent;
use Molitor\Shop\Http\Livewire\ProductsListComponent;
use Molitor\Shop\Http\Livewire\ProductGalleryComponent;
use Molitor\Shop\Http\Livewire\ProductsFilterComponent;
use Molitor\Shop\Http\Livewire\SidebarCategoriesComponent;
use Molitor\Shop\Repositories\CartProductRepository;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class ShopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'shop');
        // Register package translations under the "shop" namespace
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'shop');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register Blade component namespace for package components
        Blade::componentNamespace('Molitor\\Shop\\View\\Components', 'shop');

        // Register Livewire components (package namespace)
        Livewire::component('shop.cart', CartComponent::class);
        Livewire::component('shop.products-list', ProductsListComponent::class);
        Livewire::component('shop.header-cart', HeaderCartComponent::class);
        Livewire::component('shop.product-gallery', ProductGalleryComponent::class);
        Livewire::component('shop.products-filter', ProductsFilterComponent::class);
        Livewire::component('shop.sidebar-categories', SidebarCategoriesComponent::class);

        // Publish public assets (e.g., fallback images) to public/vendor/shop
        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('vendor/shop'),
        ], 'public');
    }

    public function register()
    {
        $this->app->bind(CartProductRepositoryInterface::class, CartProductRepository::class);
    }
}
