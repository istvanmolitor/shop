<?php

namespace Molitor\Shop\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Molitor\Product\Models\ProductCategory;
use Molitor\Shop\Repositories\CartProductRepository;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class ShopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'shop');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Share product categories with the shop layout (left sidebar)
        View::composer('shop::layouts.shop', function ($view) {
            $categories = ProductCategory::query()
                ->whereNull('parent_id')
                ->orderBy('left_value')
                ->get();

            $view->with('shopCategories', $categories);
        });
    }

    public function register()
    {
        $this->app->bind(CartProductRepositoryInterface::class, CartProductRepository::class);
    }
}
