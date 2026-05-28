<?php

namespace Molitor\Shop\Tests\Feature;

use Illuminate\Support\ServiceProvider;
use Molitor\Shop\Providers\ShopServiceProvider;
use Tests\TestCase;

class PackageSmokeTest extends TestCase
{
    public function test_service_provider_is_loaded(): void
    {
        require_once __DIR__.'/../../src/Providers/ShopServiceProvider.php';

        $this->assertTrue(class_exists(ShopServiceProvider::class));
        $this->assertTrue(is_subclass_of(ShopServiceProvider::class, ServiceProvider::class));
    }
}


