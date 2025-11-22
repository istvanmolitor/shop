<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Molitor\Product\Models\Product;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;

class CartController extends BaseController
{
    public function __construct(private readonly CartProductRepositoryInterface $cart)
    {
    }

    protected function owner(): array
    {
        $userId = auth()->check() ? (int)auth()->id() : null;
        $sessionId = session()->getId();
        return [$userId, $sessionId];
    }

    public function index(): View
    {
        [$userId, $sessionId] = $this->owner();
        $items = $this->cart->getAllByOwner($userId, $sessionId);

        $total = 0.0;
        foreach ($items as $item) {
            $price = (float)($item->product->price ?? 0);
            $total += $price * (int)$item->quantity;
        }

        return view('shop::cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);
        $qty = (int)($data['quantity'] ?? 1);

        [$userId, $sessionId] = $this->owner();
        $this->cart->addOrIncrement($userId, $sessionId, $product->id, $qty);

        return redirect()
            ->route('shop.cart.index')
            ->with('status', 'A termék bekerült a kosárba.');
    }
}
