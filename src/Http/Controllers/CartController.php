<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Molitor\Currency\Services\Price;
use Molitor\Product\Models\Product;
use Molitor\Shop\Models\CartProduct;
use Molitor\Shop\Repositories\CartProductRepositoryInterface;
use Molitor\Shop\Services\Owner;

class CartController extends BaseController
{
    public function __construct(private readonly CartProductRepositoryInterface $cart)
    {
    }

    public function index(): View
    {
        $items = $this->cart->getAllByOwner(new Owner());

        $total = new Price(0, null);

        /** @var CartProduct $item */
        foreach ($items as $item) {
            /** @var Product $product */
            $product = $item->product;
            $price = $product->getPrice();
            $total = $total->addition($price->multiple($item->quantity));
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

        $this->cart->addOrIncrement(new Owner(), $product->id, $qty);

        return redirect()
            ->route('shop.cart.index')
            ->with('status', 'A termék bekerült a kosárba.');
    }
}
