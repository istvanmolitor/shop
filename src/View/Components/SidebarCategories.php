<?php

namespace Molitor\Shop\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Molitor\Product\Repositories\ProductCategoryRepositoryInterface;

class SidebarCategories extends Component
{
    /**
     * The root categories to display in the sidebar.
     *
     * @var \Illuminate\Support\Collection
     */
    public $shopCategories;

    public function __construct()
    {
        /** @var ProductCategoryRepositoryInterface $productCategoryRepository */
        $productCategoryRepository = app(ProductCategoryRepositoryInterface::class);
        $this->shopCategories =  $productCategoryRepository->getAll();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract
    {
        return view('shop::components.sidebar-categories', [
            'shopCategories' => $this->shopCategories,
        ]);
    }
}
