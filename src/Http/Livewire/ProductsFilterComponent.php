<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Molitor\Product\Models\ProductField;

class ProductsFilterComponent extends Component
{
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    /**
     * Selected attribute option IDs grouped by field ID.
     * Example: [ fieldId => [optionId, ...] ]
     * @var array<int, array<int>>
     */
    public array $selectedOptions = [];

    public function updated($name): void
    {
        $this->emitFilters();
    }

    public function toggleOption(int $fieldId, int $optionId): void
    {
        $current = $this->selectedOptions[$fieldId] ?? [];
        if (in_array($optionId, $current, true)) {
            $current = array_values(array_filter($current, fn ($id) => (int)$id !== $optionId));
        } else {
            $current[] = $optionId;
        }
        $this->selectedOptions[$fieldId] = $current;
        $this->emitFilters();
    }

    private function emitFilters(): void
    {
        $this->dispatch('filtersUpdated', [
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'attributes' => $this->selectedOptions,
        ])->to('shop.products-list');
    }

    public function render(): View
    {
        $fields = ProductField::query()
            ->with(['productFieldOptions'])
            ->joinTranslation()
            ->orderBy('product_field_translations.name')
            ->get();

        return view('shop::livewire.products-filter-component', [
            'fields' => $fields,
        ]);
    }
}
