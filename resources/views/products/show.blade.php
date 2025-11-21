<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termék részletei</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { display: flex; align-items: center; gap: 1rem; }
        .header img { max-width: 180px; max-height: 180px; object-fit: cover; border: 1px solid #e5e7eb; border-radius: 6px; }
        .meta { color: #6b7280; font-size: 0.95rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; background: #fff; }
        .card h2 { margin-top: 0; font-size: 1.1rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; }
        th { background: #f3f4f6; }
        .price { font-weight: 600; }
        .gallery { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .gallery img { width: 120px; height: 120px; object-fit: cover; border: 1px solid #e5e7eb; border-radius: 6px; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <p><a href="{{ route('shop.products.index') }}">← Vissza a listához</a></p>

    <div class="card">
        <div class="header">
            @php
                $mainImage = $product->productImages->firstWhere('is_main', true) ?? $product->productImages->first();
            @endphp
            @if($mainImage && ($mainImage->image_url || $mainImage->image))
                <img src="{{ $mainImage->image_url ?: $mainImage->image }}" alt="{{ $product->name }}">
            @endif
            <div>
                <h1 style="margin: 0 0 .25rem 0;">{{ $product->name }}</h1>
                <div class="meta">
                    ID: {{ $product->id }} • SKU: {{ $product->sku }} • Slug: {{ $product->slug }}
                </div>
                <div class="price" style="margin-top:.5rem;">
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

    <div class="grid" style="margin-top:1rem;">
        <div class="card">
            <h2>Kategóriák</h2>
            @if($product->productCategories->isEmpty())
                <p>Nincs kategória megadva.</p>
            @else
                <ul style="margin:0; padding-left: 1.1rem;">
                    @foreach($product->productCategories as $category)
                        <li>{{ $category->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="card">
            <h2>Vonalkódok</h2>
            @if($product->barcodes->isEmpty())
                <p>Nincs vonalkód megadva.</p>
            @else
                <ul style="margin:0; padding-left: 1.1rem;">
                    @foreach($product->barcodes as $barcode)
                        <li>{{ $barcode->barcode ?? $barcode->code ?? '' }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="card" style="margin-top:1rem;">
        <h2>Jellemzők</h2>
        @php
            $attributes = $product->productAttributes->map(function($attr){
                $opt = $attr->productFieldOption;
                return $opt ? ($opt->productField->name . ': ' . $opt->name) : null;
            })->filter();
        @endphp
        @if($attributes->isEmpty())
            <p>Nincsenek jellemzők.</p>
        @else
            <ul style="margin:0; padding-left: 1.1rem; columns: 2;">
                @foreach($attributes as $text)
                    <li>{{ $text }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="card" style="margin-top:1rem;">
        <h2>Képgaléria</h2>
        @if($product->productImages->isEmpty())
            <p>Nincsenek képek.</p>
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
</div>
</body>
</html>
