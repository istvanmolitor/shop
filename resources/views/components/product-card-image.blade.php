
<a href="{{ route('shop.products.show', $product) }}" class="relative block pt-[66%] bg-slate-100" aria-label="{{ $product->name }}">
    @php($fallback = asset('vendor/shop/product/noimage.png'))
    @php($src = $imgUrl ?: $fallback)
    <img class="absolute inset-0 w-full h-full object-cover" src="{{ $src }}" alt="{{ $product->name }}">
</a>
