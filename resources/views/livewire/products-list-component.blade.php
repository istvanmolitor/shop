<div>
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
