<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Molitor\Currency\Services\Price;
use Molitor\Shop\Services\CartService;

class HeaderCartComponent extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    #[Computed]
    public function count(): int
    {
        return app(CartService::class)->count();
    }

    #[Computed]
    public function items()
    {
        return app(CartService::class)->getItems();
    }

    #[Computed]
    public function total(): Price
    {
        return app(CartService::class)->getTotal();
    }

    public function render(): View
    {
        return view('shop::livewire.header-cart-component');
    }
}
