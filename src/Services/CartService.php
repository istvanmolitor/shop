<?php

namespace Molitor\Shop\Services;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Currency\Services\Price;
use Molitor\Product\Models\Product;
use Molitor\Shop\Models\CartProduct;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class CartService
{
    private Owner $owner;

    public function __construct(private readonly CartProductRepositoryInterface $cartRepository)
    {
        $this->owner = new Owner();
    }

    public function getItems(): Collection
    {
        return $this->cartRepository->getAllByOwner($this->owner);
    }

    public function getTotal(): Price
    {
        $items = $this->getItems();
        $total = new Price(0, null);

        /** @var CartProduct $item */
        foreach ($items as $item) {
            /** @var Product $product */
            $product = $item->product;
            $price = $product->getPrice();
            $total = $total->addition($price->multiple($item->quantity));
        }

        return $total;
    }

    public function addProduct(int $productId, int $quantity = 1): CartProduct
    {
        return $this->cartRepository->addOrIncrement($this->owner, $productId, $quantity);
    }

    public function count(): int
    {
        return $this->cartRepository->count($this->owner);
    }
}
