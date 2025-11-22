@extends('shop::layouts.shop')

@section('title', ($product->name ?? 'Termék') . ' – Molitor Shop')
@section('page_title', $product->name)
@section('page_subtitle')ID: {{ $product->id }} • SKU: {{ $product->sku }} • Slug: {{ $product->slug }}@endsection

@section('content')
    @php
        $mainImage = $product->productImages->firstWhere('is_main', true) ?? $product->productImages->first();
        $mainUrl = $mainImage?->image_url ?: $mainImage?->image;
    @endphp

    <p><a class="inline-flex items-center gap-1 font-medium text-slate-700 hover:text-slate-900 no-underline" href="{{ route('shop.products.index') }}">← Vissza a listához</a></p>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                <div class="relative pt-[70%] bg-slate-100">
                    @if($mainUrl)
                        <img class="absolute inset-0 w-full h-full object-cover" src="{{ $mainUrl }}" alt="{{ $product->name }}">
                    @endif
                </div>
                <div class="p-4">
                    <div class="font-bold text-blue-700">
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
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">
            <h2 class="mt-0 text-lg font-semibold">Részletek</h2>
            <table class="w-full border-collapse text-sm">
                <tbody>
                <tr class="border-t first:border-t-0 border-slate-200"><th class="w-44 text-left bg-slate-50 p-2">Azonosító</th><td class="p-2">{{ $product->id }}</td></tr>
                <tr class="border-t border-slate-200"><th class="text-left bg-slate-50 p-2">SKU</th><td class="p-2">{{ $product->sku }}</td></tr>
                <tr class="border-t border-slate-200"><th class="text-left bg-slate-50 p-2">Slug</th><td class="p-2">{{ $product->slug }}</td></tr>
                <tr class="border-t border-slate-200">
                    <th class="text-left bg-slate-50 p-2">Egységár</th>
                    <td class="p-2">
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

    <div class="grid gap-4 sm:grid-cols-2 mt-4">
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">
            <h2 class="mt-0 text-lg font-semibold">Kategóriák</h2>
            @if($product->productCategories->isEmpty())
                <p class="text-slate-500">Nincs kategória megadva.</p>
            @else
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($product->productCategories as $category)
                        <li>{{ $category->name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">
            <h2 class="mt-0 text-lg font-semibold">Vonalkódok</h2>
            @if($product->barcodes->isEmpty())
                <p class="text-slate-500">Nincs vonalkód megadva.</p>
            @else
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($product->barcodes as $barcode)
                        <li>{{ $barcode->barcode ?? $barcode->code ?? '' }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 mt-4">
        <h2 class="mt-0 text-lg font-semibold">Jellemzők</h2>
        @php
            $attributes = $product->productAttributes->map(function($attr){
                $opt = $attr->productFieldOption;
                return $opt ? ($opt->productField->name . ': ' . $opt->name) : null;
            })->filter();
        @endphp
        @if($attributes->isEmpty())
            <p class="text-slate-500">Nincsenek jellemzők.</p>
        @else
            <ul class="columns-2 gap-4 [column-fill:_balance] list-disc pl-5">
                @foreach($attributes as $text)
                    <li class="break-inside-avoid">{{ $text }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 mt-4">
        <h2 class="mt-0 text-lg font-semibold">Képgaléria</h2>
        @if($product->productImages->isEmpty())
            <p class="text-slate-500">Nincsenek képek.</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($product->productImages as $image)
                    @if($image->image_url || $image->image)
                        <img class="w-28 h-28 object-cover rounded-md border border-slate-200" src="{{ $image->image_url ?: $image->image }}" alt="{{ $image->name ?? $product->name }}">
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection
