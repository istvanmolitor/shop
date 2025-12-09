<?php

namespace Molitor\Shop\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Shop\Models\CartProduct;
use Molitor\Shop\Services\Owner;

interface CartProductRepositoryInterface
{
    public function getAllByOwner(Owner $owner): Collection;

    public function findOne(Owner $owner, int $productId): ?CartProduct;

    public function addOrIncrement(Owner $owner, int $productId, int $qty = 1): CartProduct;

    public function updateQuantity(CartProduct $item, int $qty): CartProduct;

    public function remove(CartProduct $item): void;

    public function clear(Owner $owner): void;

    public function count(Owner $owner): int;
}
