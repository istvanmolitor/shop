<?php

declare(strict_types=1);

namespace Molitor\Shop\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Shop\Models\CartProduct;
use Molitor\Shop\Services\Owner;

class CartProductRepository implements CartProductRepositoryInterface
{
    public function __construct(private readonly CartProduct $model = new CartProduct())
    {
    }

    protected function ownerScope(Owner $owner)
    {
        $query = $this->model->newQuery();
        if ($owner->getUserId()) {
            $query->where('user_id', $owner->getUserId());
        } elseif ($owner->getSessionId()) {
            $query->where('session_id', $owner->getSessionId());
        } else {
            $query->whereRaw('1 = 0');
        }
        return $query;
    }

    public function getAllByOwner(Owner $owner): Collection
    {
        return $this->ownerScope($owner)
            ->with('product.productImages')
            ->orderByDesc('id')
            ->get();
    }

    public function findOne(Owner $owner, int $productId): ?CartProduct
    {
        return $this->ownerScope($owner)
            ->where('product_id', $productId)
            ->first();
    }

    public function addOrIncrement(Owner $owner, int $productId, int $qty = 1): CartProduct
    {
        $qty = max(1, $qty);

        $existing = $this->findOne($owner, $productId);
        if ($existing) {
            $existing->quantity += $qty;
            $existing->save();
            return $existing;
        }

        $item = new CartProduct();
        $item->user_id = $owner->getUserId();
        $item->session_id = $owner->getSessionId();
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

    public function clear(Owner $owner): void
    {
        $this->ownerScope($owner)->delete();
    }

    public function count(Owner $owner): int
    {
        return (int) $this->ownerScope($owner)->sum('quantity');
    }
}
