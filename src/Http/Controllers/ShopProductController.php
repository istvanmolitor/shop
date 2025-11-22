<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Molitor\Product\Models\Product;

class ShopProductController extends BaseController
{
    public function index()
    {
        // Listing is handled by Livewire component; just render the page
        return view('shop::products.index');
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
