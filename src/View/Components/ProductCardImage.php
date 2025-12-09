<?php

namespace Molitor\Shop\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Molitor\Product\Models\Product;

class ProductCardImage extends Component
{
    /**
     * The product to display the image for.
     */
    public Product $product;

    /**
     * The main image of the product.
     */
    public $img;

    /**
     * The image URL.
     */
    public ?string $imgUrl;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->img = $product->mainImage;
        $this->imgUrl = $this->img?->getSrc();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract
    {
        return view('shop::components.product-card-image');
    }
}

