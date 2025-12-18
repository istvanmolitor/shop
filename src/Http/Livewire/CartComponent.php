<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Livewire\Component;
use Molitor\Shop\Models\CartProduct;
use Molitor\Shop\Services\CartService;

class CartComponent extends Component
{
    public array $qty = [];

    public float $total = 0.0;

    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function mount(): void
    {
        $this->refreshItems();
    }

    protected function refreshItems(): void
    {
        $this->total = $this->cartService->getTotal()->price;
        $this->qty = [];
        $items = $this->cartService->getItems();
        foreach ($items as $item) {
            // Use product_id as key for session-based carts
            $key = $item->id ?? 'p_' . $item->product_id;
            $this->qty[$key] = (int)$item->quantity;
        }
    }

    protected function getItems()
    {
        return $this->cartService->getItems();
    }

    public function incrementQty($itemKey): void
    {
        $item = $this->findOwnedItem($itemKey);
        if (!$item) return;
        $this->cartService->updateQuantity($item, ((int)$item->quantity) + 1);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    public function decrementQty($itemKey): void
    {
        $item = $this->findOwnedItem($itemKey);
        if (!$item) return;
        $new = ((int)$item->quantity) - 1;
        $this->cartService->updateQuantity($item, $new);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    public function saveQty($itemKey): void
    {
        $value = (int)($this->qty[$itemKey] ?? 0);
        $item = $this->findOwnedItem($itemKey);
        if (!$item) return;
        $this->cartService->updateQuantity($item, $value);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    public function removeItem($itemKey): void
    {
        $item = $this->findOwnedItem($itemKey);
        if (!$item) return;
        $this->cartService->remove($item);
        $this->refreshItems();
        $this->dispatch('cart-updated');
    }

    protected function findOwnedItem($itemKey): ?CartProduct
    {
        $items = $this->cartService->getItems();

        // If itemKey starts with 'p_', it's a session cart item (product_id)
        if (is_string($itemKey) && str_starts_with($itemKey, 'p_')) {
            $productId = (int)substr($itemKey, 2);
            foreach ($items as $item) {
                if ($item->product_id === $productId) {
                    return $item;
                }
            }
            return null;
        }

        // Otherwise it's a database ID
        foreach ($items as $item) {
            if ($item->id === $itemKey) {
                return $item;
            }
        }

        return null;
    }

    public function render()
    {
        return view('shop::livewire.cart-component', [
            'items' => $this->getItems(),
        ]);
    }
}
