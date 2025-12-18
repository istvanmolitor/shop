<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Molitor\Shop\Http\Requests\StoreCartRequest;
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

    public function store(StoreCartRequest $request): RedirectResponse
    {
        $this->cartService->addProduct($request->product_id, $request->quantity ?? 1);

        return redirect()
            ->route('shop.cart.index')
            ->with('status', __('shop::common.cart.product_added'));
    }
}
