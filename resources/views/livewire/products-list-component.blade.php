<div>
    <div class="mb-4 flex items-center justify-end gap-2">
        <label for="sort" class="text-sm text-slate-600">Rendezés:</label>
        <select id="sort" wire:model.live="sort" class="border border-slate-300 rounded-md px-2 py-1 text-sm">
            <option value="id_desc">Legújabb elöl</option>
            <option value="name_asc">Név (A–Z)</option>
            <option value="name_desc">Név (Z–A)</option>
            <option value="price_asc">Ár (növekvő)</option>
            <option value="price_desc">Ár (csökkenő)</option>
        </select>
    </div>
    @if($products->isEmpty())
        <p class="text-slate-500">Nincs megjeleníthető termék.</p>
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
