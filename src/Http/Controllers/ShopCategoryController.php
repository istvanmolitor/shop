<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Molitor\Product\Models\ProductCategory;

class ShopCategoryController extends BaseController
{
    public function show(ProductCategory $productCategory)
    {
        // Eager-load direct child categories for card list rendering
        $productCategory->load('productCategories');

        return view('shop::categories.show', [
            'category' => $productCategory,
        ]);
    }
}
