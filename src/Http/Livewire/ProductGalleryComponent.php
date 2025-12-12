<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Molitor\Product\Models\Product;

class ProductGalleryComponent extends Component
{
    public int $productId;

    /** @var array<int, array{url:string|null, alt:string}> */
    public array $images = [];

    public int $currentIndex = 0;
    public bool $showLightbox = false;

    public function mount(int $productId): void
    {
        $product = Product::query()->with(['productImages' => function ($q) {
            $q->orderBy('sort')->orderBy('id');
        }])->findOrFail($productId);

        $this->images = $product->productImages->map(function ($img) use ($product) {
            /** @var \Molitor\Product\Models\ProductImage $img */
            return [
                'url' => $img->getSrc(),
                'alt' => $product->name,
                'is_main' => (bool)($img->is_main ?? false),
            ];
        })->values()->all();

        // Set current index to main image if exists
        $mainIndex = collect($this->images)->search(fn ($i) => !empty($i['is_main']));
        if ($mainIndex !== false) {
            $this->currentIndex = (int)$mainIndex;
        } else {
            $this->currentIndex = 0;
        }
    }

    public function select(int $index): void
    {
        if ($index >= 0 && $index < count($this->images)) {
            $this->currentIndex = $index;
        }
    }

    public function openLightbox(): void
    {
        $this->showLightbox = true;
    }

    public function closeLightbox(): void
    {
        $this->showLightbox = false;
    }

    public function prev(): void
    {
        $count = count($this->images);
        if ($count === 0) {
            return;
        }
        $this->currentIndex = ($this->currentIndex - 1 + $count) % $count;
    }

    public function next(): void
    {
        $count = count($this->images);
        if ($count === 0) {
            return;
        }
        $this->currentIndex = ($this->currentIndex + 1) % $count;
    }

    public function render(): View
    {
        return view('shop::livewire.product-gallery-component');
    }
}
