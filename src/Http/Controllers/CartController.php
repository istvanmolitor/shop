<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Molitor\Product\Models\Product;
use Molitor\Shop\Services\CartService;

class CartController extends BaseController
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(): View
    {
        return view('shop::cart.index', [
            'items' => $this->cartService->getItems(),
            'total' => $this->cartService->getTotal(),
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

        $this->cartService->addProduct($product->id, $qty);

        return redirect()
            ->route('shop.cart.index')
            ->with('status', 'A termék bekerült a kosárba.');
    }
}
