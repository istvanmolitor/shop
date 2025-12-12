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
use Molitor\Shop\Repositories\CartProductRepository;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class ShopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'shop');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register Blade component namespace for package components
        Blade::componentNamespace('Molitor\\Shop\\View\\Components', 'shop');

        // Register Livewire components (package namespace)
        // Alias matches Livewire's auto-generated slug for FQCN-based mounting
        Livewire::component('molitor.shop.http.livewire.cart-component', CartComponent::class);
        Livewire::component('molitor.shop.http.livewire.products-list-component', ProductsListComponent::class);
        Livewire::component('molitor.shop.http.livewire.header-cart-component', HeaderCartComponent::class);
        Livewire::component('molitor.shop.http.livewire.product-gallery-component', ProductGalleryComponent::class);
    }

    public function register()
    {
        $this->app->bind(CartProductRepositoryInterface::class, CartProductRepository::class);
    }
}
