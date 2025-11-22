@props(['product'])
@php
    $img = optional($product->productImages->first());
    $imgUrl = $img?->image_url ?: $img?->image;
    $currency = $product->currency?->code;
@endphp

<div class="bg-white border border-slate-200 rounded-lg overflow-hidden flex flex-col shadow-sm">
    <a href="{{ route('shop.products.show', $product) }}" class="relative block pt-[66%] bg-slate-100" aria-label="{{ $product->name }}">
        @if($imgUrl)
            <img class="absolute inset-0 w-full h-full object-cover" src="{{ $imgUrl }}" alt="{{ $product->name }}">
        @endif
    </a>
    <div class="p-3 grid gap-1.5">
        <a href="{{ route('shop.products.show', $product) }}" class="font-semibold text-slate-900 hover:text-slate-950 no-underline">{{ $product->name }}</a>
        <div class="text-slate-500">SKU: {{ $product->sku }}</div>
        <div class="font-bold text-blue-700">
            {{ number_format((float)($product->price ?? 0), 2, ',', ' ') }} @if($currency) {{ $currency }} @endif
        </div>
        <div class="pt-1 flex items-center gap-2">
            <a class="inline-flex items-center gap-2 border border-blue-600 bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 no-underline" href="{{ route('shop.products.show', $product) }}">Megnézem</a>
            <form method="post" action="{{ route('shop.cart.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-3 py-2 rounded-md hover:bg-emerald-700">Kosárba</button>
            </form>
        </div>
    </div>
</div>
