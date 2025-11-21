<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termékek</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; }
        th { background: #f3f4f6; }
        tr:nth-child(even) { background: #fafafa; }
        .price { text-align: right; white-space: nowrap; }
    </style>
</head>
<body>
<h1>Termékek</h1>
@if($products->isEmpty())
    <p>Nincs megjeleníthető termék.</p>
@else
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>SKU</th>
            <th>Név</th>
            <th>Ár</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->sku }}</td>
                <td><a href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a></td>
                <td class="price">
                    {{ number_format((float)($product->price ?? 0), 2, ',', ' ') }}
                    @if($product->currency)
                        {{ $product->currency->code }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
</body>
</html>
