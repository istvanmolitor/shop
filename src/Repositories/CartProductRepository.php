<?php

declare(strict_types=1);

namespace Molitor\Shop\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Shop\Models\CartProduct;

class CartProductRepository implements CartProductRepositoryInterface
{
    public function __construct(private readonly CartProduct $model = new CartProduct())
    {
    }

    protected function ownerScope(?int $userId, ?string $sessionId)
    {
        $query = $this->model->newQuery();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            // No owner - return empty scope that matches none
            $query->whereRaw('1 = 0');
        }
        return $query;
    }

    public function getAllByOwner(?int $userId, ?string $sessionId): Collection
    {
        return $this->ownerScope($userId, $sessionId)
            ->with('product')
            ->orderByDesc('id')
            ->get();
    }

    public function findOne(?int $userId, ?string $sessionId, int $productId): ?CartProduct
    {
        return $this->ownerScope($userId, $sessionId)
            ->where('product_id', $productId)
            ->first();
    }

    public function addOrIncrement(?int $userId, ?string $sessionId, int $productId, int $qty = 1): CartProduct
    {
        $qty = max(1, $qty);

        $existing = $this->findOne($userId, $sessionId, $productId);
        if ($existing) {
            $existing->quantity += $qty;
            $existing->save();
            return $existing;
        }

        $item = new CartProduct();
        $item->user_id = $userId;
        $item->session_id = $sessionId;
        $item->product_id = $productId;
        $item->quantity = $qty;
        $item->save();
        return $item;
    }

    public function updateQuantity(CartProduct $item, int $qty): CartProduct
    {
        $item->quantity = max(0, $qty);
        if ($item->quantity === 0) {
            $item->delete();
        } else {
            $item->save();
        }
        return $item;
    }

    public function remove(CartProduct $item): void
    {
        $item->delete();
    }

    public function clear(?int $userId, ?string $sessionId): void
    {
        $this->ownerScope($userId, $sessionId)->delete();
    }

    public function count(?int $userId, ?string $sessionId): int
    {
        return (int) $this->ownerScope($userId, $sessionId)->sum('quantity');
    }
}
