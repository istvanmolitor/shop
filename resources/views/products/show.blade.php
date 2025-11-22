@extends('shop::layouts.shop')

@section('title', ($product->name ?? 'Termék') . ' – Molitor Shop')
@section('page_title', $product->name)
@section('page_subtitle')ID: {{ $product->id }} • SKU: {{ $product->sku }} • Slug: {{ $product->slug }}@endsection

@section('content')
    @php
        $mainImage = $product->productImages->firstWhere('is_main', true) ?? $product->productImages->first();
        $mainUrl = $mainImage?->image_url ?: $mainImage?->image;
    @endphp

    <p><a class="back" href="{{ route('shop.products.index') }}">← Vissza a listához</a></p>

    <div class="details">
        <div>
            <div class="card">
                <div class="thumb" style="padding-top: 70%">
                    @if($mainUrl)
                        <img src="{{ $mainUrl }}" alt="{{ $product->name }}">
                    @endif
                </div>
                <div class="body">
                    <div class="price">
                        {{ number_format((float)($product->price ?? 0), 2, ',', ' ') }}
                        @if($product->currency)
                            {{ $product->currency->code }}
                        @endif
                        @if($product->productUnit)
                            / {{ $product->productUnit->name }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="padding:1rem;">
            <h2 style="margin-top:0;">Részletek</h2>
            <table class="table">
                <tbody>
                <tr><th style="width:180px;">Azonosító</th><td>{{ $product->id }}</td></tr>
                <tr><th>SKU</th><td>{{ $product->sku }}</td></tr>
                <tr><th>Slug</th><td>{{ $product->slug }}</td></tr>
                <tr>
                    <th>Egységár</th>
                    <td>
                        {{ number_format((float)($product->price ?? 0), 2, ',', ' ') }}
                        @if($product->currency)
                            {{ $product->currency->code }}
                        @endif
                        @if($product->productUnit)
                            / {{ $product->productUnit->name }}
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-2" style="margin-top:1rem;">
        <div class="card" style="padding:1rem;">
            <h2 style="margin-top:0;">Kategóriák</h2>
            @if($product->productCategories->isEmpty())
                <p class="muted">Nincs kategória megadva.</p>
            @else
                <ul>
                    @foreach($product->productCategories as $category)
                        <li>{{ $category->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="card" style="padding:1rem;">
            <h2 style="margin-top:0;">Vonalkódok</h2>
            @if($product->barcodes->isEmpty())
                <p class="muted">Nincs vonalkód megadva.</p>
            @else
                <ul>
                    @foreach($product->barcodes as $barcode)
                        <li>{{ $barcode->barcode ?? $barcode->code ?? '' }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="card" style="padding:1rem; margin-top:1rem;">
        <h2 style="margin-top:0;">Jellemzők</h2>
        @php
            $attributes = $product->productAttributes->map(function($attr){
                $opt = $attr->productFieldOption;
                return $opt ? ($opt->productField->name . ': ' . $opt->name) : null;
            })->filter();
        @endphp
        @if($attributes->isEmpty())
            <p class="muted">Nincsenek jellemzők.</p>
        @else
            <ul style="columns: 2; -moz-columns: 2; -webkit-columns: 2;">
                @foreach($attributes as $text)
                    <li>{{ $text }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="card" style="padding:1rem; margin-top:1rem;">
        <h2 style="margin-top:0;">Képgaléria</h2>
        @if($product->productImages->isEmpty())
            <p class="muted">Nincsenek képek.</p>
        @else
            <div class="gallery">
                @foreach($product->productImages as $image)
                    @if($image->image_url || $image->image)
                        <img src="{{ $image->image_url ?: $image->image }}" alt="{{ $image->name ?? $product->name }}">
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection
