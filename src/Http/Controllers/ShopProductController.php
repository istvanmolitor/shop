<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Molitor\Product\Models\Product;
use Molitor\Stock\Services\StockService;

class ShopProductController extends BaseController
{
    public function index()
    {
        // Listing is handled by Livewire component; just render the page
        return view('shop::products.index');
    }

    public function show(Product $product, StockService $stockService)
    {
        $product->load([
            'productUnit',
            'productImages',
            'productAttributes.productFieldOption.productField',
            'barcodes',
            'productCategories',
        ]);

        // Aggregate stock across all locations (null location)
        $stock = $stockService->getStock(null, $product);
        $inStock = $stock > 0;

        return view('shop::products.show', compact('product', 'stock', 'inStock'));
    }
}
