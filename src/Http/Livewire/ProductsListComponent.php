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

    protected $queryString = [
        'q' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount(): void
    {
        $this->q = (string)request()->query('q', '');
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    protected function query(): Builder
    {
        $product = new Product();
        $translationTable = $product->getTranslationTable();

        $query = Product::query()
            ->with(['currency', 'productImages'])
            ->joinTranslation()
            ->selectBase()
            ->orderByDesc('id');

        if (trim($this->q) !== '') {
            $term = '%' . str_replace(['%', '_'], ['\\%', '\\_'], trim($this->q)) . '%';
            $query->where(function ($q) use ($term, $translationTable) {
                $q->where($translationTable . '.name', 'like', $term)
                  ->orWhere('products.sku', 'like', $term);
            });
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
