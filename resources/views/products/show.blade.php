@extends('shop::layouts.shop')

@section('title', ($product->name ?? 'Termék') . ' – Molitor Shop')
@section('page_title', $product->name)

@section('content')
    @php
        $mainImage = $product->productImages->firstWhere('is_main', true) ?? $product->productImages->first();
        $mainUrl = $mainImage?->getSrc();
    @endphp

    <p class="flex items-center justify-between">
        <a class="inline-flex items-center gap-1 font-medium text-slate-700 hover:text-slate-900 no-underline" href="{{ route('shop.products.index') }}">← Vissza a listához</a>
        @can('acl', 'product')
            <a
                href="{{ \Molitor\Product\Filament\Resources\ProductResource::getUrl('edit', ['record' => $product]) }}"
                class="inline-flex items-center gap-1 text-slate-500 hover:text-slate-700 no-underline"
                title="Termék szerkesztése"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM3 21h3.75L17.81 9.94l-3.75-3.75L3 17.25V21Z"/>
                </svg>
            </a>
        @endcan
    </p>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            @livewire('molitor.shop.http.livewire.product-gallery-component', ['productId' => $product->id])

            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm mt-4">
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
                <tr class="border-t border-slate-200"><th class="text-left bg-slate-50 p-2">SKU</th><td class="p-2">{{ $product->sku }}</td></tr>
                @php
                    // Collect attributes as [label => value]
                    $detailAttributes = $product->productAttributes->map(function($attr){
                        $opt = $attr->productFieldOption;
                        return $opt ? [
                            'label' => $opt->productField->name,
                            'value' => $opt->name,
                        ] : null;
                    })->filter();
                @endphp
                @foreach($detailAttributes as $attr)
                    <tr class="border-t border-slate-200">
                        <th class="text-left bg-slate-50 p-2">{{ $attr['label'] }}</th>
                        <td class="p-2">{{ $attr['value'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($product->description)
        <div class="mt-4 prose prose-slate">
            {{ $product->description }}
        </div>
    @endif
@endsection
