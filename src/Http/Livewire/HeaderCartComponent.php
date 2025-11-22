<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class HeaderCartComponent extends Component
{
    public int $count = 0;
    public float $total = 0.0;
    public $items; // Eloquent Collection (serialized by Livewire)

    protected CartProductRepositoryInterface $cart;

    protected $listeners = ['cart-updated' => 'refreshItems'];

    public function boot(CartProductRepositoryInterface $cart): void
    {
        $this->cart = $cart;
    }

    public function mount(): void
    {
        $this->refreshItems();
    }

    protected function owner(): array
    {
        $userId = auth()->check() ? (int)auth()->id() : null;
        $sessionId = session()->getId();
        return [$userId, $sessionId];
    }

    public function refreshItems(): void
    {
        [$userId, $sessionId] = $this->owner();
        $this->items = $this->cart->getAllByOwner($userId, $sessionId);
        $this->count = $this->cart->count($userId, $sessionId);
        $this->total = 0.0;
        foreach ($this->items as $item) {
            $price = (float)($item->product->price ?? 0);
            $this->total += $price * (int)$item->quantity;
        }
    }

    public function render(): View
    {
        return view('shop::livewire.header-cart-component');
    }
}
