@extends('shop::layouts.shop')

@section('title', 'Termékek – Molitor Shop')
@section('page_title', 'Termékek')
@section('page_subtitle')Válogasson a legfrissebb termékeink közül @endsection

@section('content')
    @if($products->isEmpty())
        <p class="muted">Nincs megjeleníthető termék.</p>
    @else
        <div class="grid grid-2 grid-3">
            @foreach($products as $product)
                @php
                    $img = optional($product->productImages->first());
                    $imgUrl = $img?->image_url ?: $img?->image;
                    $currency = $product->currency?->code;
                @endphp
                <div class="card">
                    <a href="{{ route('shop.products.show', $product) }}" class="thumb" aria-label="{{ $product->name }}">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}">
                        @endif
                    </a>
                    <div class="body">
                        <a href="{{ route('shop.products.show', $product) }}" style="font-weight:600; color:inherit; text-decoration:none;">{{ $product->name }}</a>
                        <div class="muted">SKU: {{ $product->sku }}</div>
                        <div class="price">
                            {{ number_format((float)($product->price ?? 0), 2, ',', ' ') }} @if($currency) {{ $currency }} @endif
                        </div>
                        <div>
                            <a class="btn" href="{{ route('shop.products.show', $product) }}">Megnézem</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
