<?php

namespace Molitor\Shop\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Molitor\Currency\Services\Price;
use Molitor\Product\Models\Product;
use Molitor\Shop\Models\CartProduct;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class CartService
{
    const SESSION_KEY = 'cart';

    public function __construct(private readonly CartProductRepositoryInterface $cartRepository)
    {
    }

    /**
     * Get the current user (null if guest)
     */
    protected function getUser(): ?User
    {
        return auth()->check() ? auth()->user() : null;
    }

    /**
     * Get cart items from session (for guest users)
     */
    protected function getSessionCart(): array
    {
        return session()->get(static::SESSION_KEY, []);
    }

    /**
     * Save cart items to session (for guest users)
     */
    protected function setSessionCart(array $cart): void
    {
        session()->put(static::SESSION_KEY, $cart);
    }

    /**
     * Clear session cart
     */
    protected function clearSessionCart(): void
    {
        session()->forget(static::SESSION_KEY);
    }

    public function getItems(): Collection
    {
        $user = $this->getUser();

        // Authenticated user - get from database via repository
        if ($user !== null) {
            return $this->cartRepository->getAllByUser($user);
        }

        // Guest user - get from session
        $sessionCart = $this->getSessionCart();
        $collection = new Collection();

        foreach ($sessionCart as $productId => $quantity) {
            $cartProduct = new CartProduct();
            $cartProduct->product_id = (int)$productId;
            $cartProduct->quantity = (int)$quantity;
            $cartProduct->exists = false; // Mark as not persisted

            // Load the product relationship
            $cartProduct->setRelation('product', Product::with('productImages')->find($productId));

            $collection->push($cartProduct);
        }

        return $collection;
    }

    public function getTotal(): Price
    {
        $items = $this->getItems();
        $total = new Price(0, null);

        /** @var CartProduct $item */
        foreach ($items as $item) {
            /** @var Product $product */
            $product = $item->product;
            if ($product) {
                $price = $product->getPrice();
                $total = $total->addition($price->multiple($item->quantity));
            }
        }

        return $total;
    }

    public function addProduct(int $productId, int $quantity = 1): CartProduct
    {
        $user = $this->getUser();
        $quantity = max(1, $quantity);

        // Authenticated user - save to database via repository
        if ($user !== null) {
            return $this->cartRepository->addOrIncrement($user, $productId, $quantity);
        }

        // Guest user - save to session
        $sessionCart = $this->getSessionCart();
        if (isset($sessionCart[$productId])) {
            $sessionCart[$productId] += $quantity;
        } else {
            $sessionCart[$productId] = $quantity;
        }
        $this->setSessionCart($sessionCart);

        $cartProduct = new CartProduct();
        $cartProduct->product_id = $productId;
        $cartProduct->quantity = $sessionCart[$productId];
        $cartProduct->exists = false;
        return $cartProduct;
    }

    public function updateQuantity(CartProduct $item, int $quantity): CartProduct
    {
        $quantity = max(0, $quantity);

        // Authenticated user - update in database via repository
        if ($item->exists) {
            return $this->cartRepository->updateQuantity($item, $quantity);
        }

        // Guest user - update in session
        $sessionCart = $this->getSessionCart();
        if ($quantity === 0) {
            unset($sessionCart[$item->product_id]);
        } else {
            $sessionCart[$item->product_id] = $quantity;
        }
        $this->setSessionCart($sessionCart);

        $item->quantity = $quantity;
        return $item;
    }

    public function remove(CartProduct $item): void
    {
        // Authenticated user - delete from database via repository
        if ($item->exists) {
            $this->cartRepository->remove($item);
            return;
        }

        // Guest user - remove from session
        $sessionCart = $this->getSessionCart();
        unset($sessionCart[$item->product_id]);
        $this->setSessionCart($sessionCart);
    }

    public function clear(): void
    {
        $user = $this->getUser();

        // Authenticated user - clear database via repository
        if ($user !== null) {
            $this->cartRepository->clear($user);
            return;
        }

        // Guest user - clear session
        $this->clearSessionCart();
    }

    public function count(): int
    {
        $user = $this->getUser();

        // Authenticated user - count from database via repository
        if ($user !== null) {
            return $this->cartRepository->count($user);
        }

        // Guest user - count from session
        $sessionCart = $this->getSessionCart();
        return array_sum($sessionCart);
    }

    /**
     * Merge guest cart from session into authenticated user's cart
     * Call this after user login
     */
    public function mergeGuestCart(): void
    {
        $user = $this->getUser();

        // Only merge if user is logged in
        if ($user === null) {
            return;
        }

        // Get guest cart from session
        $sessionCart = $this->getSessionCart();

        if (empty($sessionCart)) {
            return;
        }

        // Merge each product from session cart into database via repository
        foreach ($sessionCart as $productId => $quantity) {
            $this->cartRepository->addOrIncrement($user, (int)$productId, (int)$quantity);
        }

        // Clear session cart after merge
        $this->clearSessionCart();
    }
}
