<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Molitor\Product\Models\Product;

class ProductsListComponent extends Component
{
    use WithPagination;

    public string $q = '';
    public int $perPage = 12;
    public int $page = 1;
    public ?int $categoryId = null;
    public string $sort = 'id_desc'; // id_desc | name_asc | name_desc | price_asc | price_desc
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    /** @var array<int, array<int>> fieldId => [optionIds] */
    public array $selectedAttributeOptions = [];

    protected $listeners = [
        'filtersUpdated' => 'onFiltersUpdated',
    ];

    protected $queryString = [
        'q' => ['except' => ''],
        'page' => ['except' => 1],
        'sort' => ['except' => 'id_desc'],
    ];

    public function mount(?int $categoryId = null): void
    {
        $this->q = (string)request()->query('q', '');
        $this->categoryId = $categoryId;
        $this->sort = $this->normalizeSort((string)request()->query('sort', $this->sort));
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->sort = $this->normalizeSort($this->sort);
        $this->resetPage();
    }

    private function normalizeSort(string $sort): string
    {
        $allowed = ['id_desc', 'name_asc', 'name_desc', 'price_asc', 'price_desc'];
        return in_array($sort, $allowed, true) ? $sort : 'id_desc';
    }

    public function onFiltersUpdated(array $filters): void
    {
        $this->minPrice = isset($filters['minPrice']) && $filters['minPrice'] !== '' ? (float)$filters['minPrice'] : null;
        $this->maxPrice = isset($filters['maxPrice']) && $filters['maxPrice'] !== '' ? (float)$filters['maxPrice'] : null;
        $this->selectedAttributeOptions = is_array($filters['attributes'] ?? null) ? $filters['attributes'] : [];
        $this->resetPage();
    }

    protected function query(): Builder
    {
        $product = new Product();
        $translationTable = $product->getTranslationTable();

        $query = Product::query()
            ->with(['productImages'])
            ->joinTranslation()
            ->selectBase();

        if ($this->categoryId) {
            $categoryId = $this->categoryId;
            $query->whereHas('productCategories', function ($q) use ($categoryId) {
                $q->where('product_categories.id', $categoryId);
            });
        }

        if (trim($this->q) !== '') {
            $term = '%' . str_replace(['%', '_'], ['\\%', '\\_'], trim($this->q)) . '%';
            $query->where(function ($q) use ($term, $translationTable) {
                $q->where($translationTable . '.name', 'like', $term)
                  ->orWhere('products.sku', 'like', $term);
            });
        }

        // Price filter
        if ($this->minPrice !== null) {
            $query->where('products.price', '>=', $this->minPrice);
        }
        if ($this->maxPrice !== null) {
            $query->where('products.price', '<=', $this->maxPrice);
        }

        // Attribute filters: require match for each selected field group
        foreach ($this->selectedAttributeOptions as $fieldId => $optionIds) {
            if (!is_array($optionIds) || count($optionIds) === 0) {
                continue;
            }
            $optionIds = array_values(array_unique(array_map('intval', $optionIds)));
            $query->whereHas('productAttributes', function (Builder $q) use ($optionIds) {
                $q->whereIn('product_field_option_id', $optionIds);
            });
        }

        // Apply sorting
        switch ($this->sort) {
            case 'name_asc':
                $query->orderBy($translationTable . '.name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy($translationTable . '.name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('products.price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('products.price', 'desc');
                break;
            case 'id_desc':
            default:
                $query->orderByDesc('products.id');
                break;
        }

        return $query;
    }

    protected function getProducts(): LengthAwarePaginator
    {
        return $this->query()->paginate($this->perPage);
    }

    public function render(): View
    {
        return view('shop::livewire.products-list-component', [
            'products' => $this->getProducts(),
        ]);
    }
}
