<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Molitor\Currency\Services\Price;
use Molitor\Product\Models\Product;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;
use Molitor\Shop\Services\Owner;

class HeaderCartComponent extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    #[Computed]
    public function count(): int
    {
        $cart = app(CartProductRepositoryInterface::class);
        $owner = new Owner();
        return $cart->count($owner);
    }

    #[Computed]
    public function items()
    {
        $cart = app(CartProductRepositoryInterface::class);
        $owner = new Owner();
        return $cart->getAllByOwner($owner);
    }

    #[Computed]
    public function total(): Price
    {
        $total = new Price(0, null);
        foreach ($this->items as $item) {
            /** @var Product $product */
            $product = $item->product;
            $price = $product->getPrice();
            $total = $total->addition($price->multiple($item->quantity));
        }
        return $total;
    }

    public function render(): View
    {
        return view('shop::livewire.header-cart-component');
    }
}
