<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Molitor\Product\Repositories\ProductCategoryRepositoryInterface;

class SidebarCategoriesComponent extends Component
{
    /**
     * Root categories displayed in the sidebar.
     * @var Collection<int, mixed>
     */
    public Collection $shopCategories;

    /**
     * Expanded category IDs.
     * @var array<int, bool>
     */
    public array $expanded = [];

    public function mount(): void
    {
        /** @var ProductCategoryRepositoryInterface $productCategoryRepository */
        $productCategoryRepository = app(ProductCategoryRepositoryInterface::class);
        $this->shopCategories = $productCategoryRepository->getRootProductCategories();
        // Load previously expanded categories from session (persist between reloads)
        $saved = session()->get('shop.sidebar_categories.expanded', []);
        if (is_array($saved)) {
            // Normalize to [int => bool]
            $normalized = [];
            foreach ($saved as $key => $val) {
                $normalized[(int)$key] = (bool)$val;
            }
            $this->expanded = $normalized;
        }
    }

    public function toggleExpand(int $categoryId): void
    {
        if (!empty($this->expanded[$categoryId])) {
            unset($this->expanded[$categoryId]);
        } else {
            $this->expanded[$categoryId] = true;
        }

        // Persist new state to session so it survives page reloads
        session()->put('shop.sidebar_categories.expanded', $this->expanded);
    }

    public function render(): View
    {
        return view('shop::livewire.sidebar-categories-component', [
            'shopCategories' => $this->shopCategories,
            'expanded' => $this->expanded,
        ]);
    }
}
