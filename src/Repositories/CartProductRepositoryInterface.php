<?php

namespace Molitor\Shop\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Molitor\Shop\Models\CartProduct;

interface CartProductRepositoryInterface
{
    public function getAllByUser(?User $user): Collection;

    public function findOne(?User $user, int $productId): ?CartProduct;

    public function addOrIncrement(?User $user, int $productId, int $qty = 1): CartProduct;

    public function updateQuantity(CartProduct $item, int $qty): CartProduct;

    public function remove(CartProduct $item): void;

    public function clear(?User $user): void;

    public function count(?User $user): int;
}
