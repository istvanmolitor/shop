<?php

namespace Molitor\Shop\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Molitor\Currency\Repositories\CurrencyRepositoryInterface;
use Molitor\Product\Models\Product;

class ProductCard extends Component
{
    /**
     * The product to display in the card.
     */
    public Product $product;

    /**
     * The currency code for the product.
     */
    public ?string $currency;

    public function __construct(
        private CurrencyRepositoryInterface $currencyRepository,
        Product $product
    )
    {
        $this->product = $product;
        $this->currency = $this->currencyRepository->getDefault()->code;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract
    {
        return view('shop::components.product-card');
    }
}

