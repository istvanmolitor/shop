<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Livewire\Component;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;
use Molitor\Shop\Models\CartProduct;
use Molitor\Shop\Services\Owner;

class CartComponent extends Component
{
    public array $qty = [];

    public $items; // Eloquent Collection (serialized by Livewire)
    public float $total = 0.0;

    protected CartProductRepositoryInterface $cart;

    public function boot(CartProductRepositoryInterface $cart): void
    {
        $this->cart = $cart;
    }

    public function mount(): void
    {
        $this->refreshItems();
    }

    protected function refreshItems(): void
    {
        $this->items = $this->cart->getAllByOwner(new Owner());
        $this->total = 0.0;
        $this->qty = [];
        foreach ($this->items as $item) {
            $price = (float)($item->product->price ?? 0);
            $this->total += $price * (int)$item->quantity;
            $this->qty[$item->id] = (int)$item->quantity;
        }
    }

    public function incrementQty(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);
        if (!$item) return;
        $this->cart->updateQuantity($item, ((int)$item->quantity) + 1);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    public function decrementQty(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);
        if (!$item) return;
        $new = ((int)$item->quantity) - 1;
        $this->cart->updateQuantity($item, $new);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    public function saveQty(int $itemId): void
    {
        $value = (int)($this->qty[$itemId] ?? 0);
        $item = $this->findOwnedItem($itemId);
        if (!$item) return;
        $this->cart->updateQuantity($item, $value);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $itemId): void
    {
        $item = $this->findOwnedItem($itemId);
        if (!$item) return;
        $this->cart->remove($item);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    protected function findOwnedItem(int $itemId): CartProduct|int
    {
        return CartProduct::query()
            ->where('id', $itemId)
            ->first();
    }

    public function render()
    {
        return view('shop::livewire.cart-component');
    }
}
