<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Molitor\Product\Models\ProductCategory;

class ShopCategoryController extends BaseController
{
    public function show(ProductCategory $productCategory)
    {
        return view('shop::categories.show', [
            'category' => $productCategory,
        ]);
    }
}
