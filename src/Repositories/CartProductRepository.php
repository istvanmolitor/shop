<?php

declare(strict_types=1);

namespace Molitor\Shop\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Molitor\Shop\Models\CartProduct;

class CartProductRepository implements CartProductRepositoryInterface
{
    public function __construct(private readonly CartProduct $model = new CartProduct())
    {
    }

    public function getAllByUser(?User $user): Collection
    {
        if ($user === null) {
            return new Collection();
        }

        return $this->model->newQuery()
            ->where('user_id', $user->id)
            ->with('product.productImages')
            ->orderByDesc('id')
            ->get();
    }

    public function findOne(?User $user, int $productId): ?CartProduct
    {
        if ($user === null) {
            return null;
        }

        return $this->model->newQuery()
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();
    }

    public function addOrIncrement(?User $user, int $productId, int $qty = 1): CartProduct
    {
        if ($user === null) {
            throw new \InvalidArgumentException('User must be authenticated to use database cart');
        }

        $qty = max(1, $qty);

        $existing = $this->findOne($user, $productId);
        if ($existing) {
            $existing->quantity += $qty;
            $existing->save();
            return $existing;
        }

        $item = new CartProduct();
        $item->user_id = $user->id;
        $item->product_id = $productId;
        $item->quantity = $qty;
        $item->save();
        return $item;
    }

    public function updateQuantity(CartProduct $item, int $qty): CartProduct
    {
        $qty = max(0, $qty);

        $item->quantity = $qty;
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

    public function clear(?User $user): void
    {
        if ($user === null) {
            return;
        }

        $this->model->newQuery()
            ->where('user_id', $user->id)
            ->delete();
    }

    public function count(?User $user): int
    {
        if ($user === null) {
            return 0;
        }

        return (int) $this->model->newQuery()
            ->where('user_id', $user->id)
            ->sum('quantity');
    }
}
