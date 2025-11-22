@props(['product'])
@php
    $img = optional($product->productImages->first());
    $imgUrl = $img?->image_url ?: $img?->image;
@endphp

<a href="{{ route('shop.products.show', $product) }}" class="relative block pt-[66%] bg-slate-100" aria-label="{{ $product->name }}">
    @if($imgUrl)
        <img class="absolute inset-0 w-full h-full object-cover" src="{{ $imgUrl }}" alt="{{ $product->name }}">
    @endif
</a>
