<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Molitor\Product\Models\Product;

class ShopProductController extends BaseController
{
    public function index()
    {
        $products = Product::query()
            ->with(['currency', 'productImages'])
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        return view('shop::products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load([
            'currency',
            'productUnit',
            'productImages',
            'productAttributes.productFieldOption.productField',
            'barcodes',
            'productCategories',
        ]);

        return view('shop::products.show', compact('product'));
    }
}
