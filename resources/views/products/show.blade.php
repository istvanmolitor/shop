@extends('shop::layouts.shop')

@section('title', ($product->name ?? 'Termék') . ' – Molitor Shop')
@section('page_title', $product->name)

@section('content')
    @php
        $mainImage = $product->productImages->firstWhere('is_main', true) ?? $product->productImages->first();
        $mainUrl = $mainImage?->getSrc();
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
                        {{ $product->getPrice() }}
                        @if($product->productUnit)
                            / {{ $product->productUnit->name }}
                        @endif
                    </div>
                    <form method="post" action="{{ route('shop.cart.store') }}" class="mt-3 flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label class="text-sm text-slate-600" for="qty">Mennyiség</label>
                        <input id="qty" name="quantity" type="number" min="1" value="1" class="w-20 border border-slate-300 rounded-md px-2 py-1">
                        <button type="submit" class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-3 py-2 rounded-md hover:bg-emerald-700">Kosárba</button>
                    </form>
                    @if(session('status'))
                        <div class="text-sm text-emerald-700 mt-2">{{ session('status') }}</div>
                    @endif
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
    @if($product->description)
        <div class="mt-4 prose prose-slate">
            {{ $product->description }}
        </div>
    @endif
@endsection
