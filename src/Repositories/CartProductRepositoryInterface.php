<?php

namespace Molitor\Shop\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Shop\Models\CartProduct;

interface CartProductRepositoryInterface
{
    public function getAllByOwner(?int $userId, ?string $sessionId): Collection;

    public function findOne(?int $userId, ?string $sessionId, int $productId): ?CartProduct;

    public function addOrIncrement(?int $userId, ?string $sessionId, int $productId, int $qty = 1): CartProduct;

    public function updateQuantity(CartProduct $item, int $qty): CartProduct;

    public function remove(CartProduct $item): void;

    public function clear(?int $userId, ?string $sessionId): void;

    public function count(?int $userId, ?string $sessionId): int;
}
