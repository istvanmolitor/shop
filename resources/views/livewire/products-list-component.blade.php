<div>
    <div class="mb-4 flex items-center justify-end gap-2">
        <label for="sort" class="text-sm text-slate-600">{{ __('shop::common.products.sort') }}</label>
        <select id="sort" wire:model.live="sort" class="border border-slate-300 rounded-md px-2 py-1 text-sm">
            <option value="id_desc">{{ __('shop::common.products.sort_options.id_desc') }}</option>
            <option value="name_asc">{{ __('shop::common.products.sort_options.name_asc') }}</option>
            <option value="name_desc">{{ __('shop::common.products.sort_options.name_desc') }}</option>
            <option value="price_asc">{{ __('shop::common.products.sort_options.price_asc') }}</option>
            <option value="price_desc">{{ __('shop::common.products.sort_options.price_desc') }}</option>
        </select>
    </div>
    @if($products->isEmpty())
        <p class="text-slate-500">{{ __('shop::common.products.no_results') }}</p>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                <x-shop::product-card :product="$product" />
            @endforeach
        </div>
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>
