@extends('shop::layouts.shop')

@section('title', ($product->name ?? 'Termék') . ' – Molitor Shop')
@section('page_title', $product->name)

@section('content')
    @php
        $mainImage = $product->productImages->firstWhere('is_main', true) ?? $product->productImages->first();
        $mainUrl = $mainImage?->getSrc();
    @endphp

    <p class="flex items-center justify-between">
        <a class="inline-flex items-center gap-1 font-medium text-slate-700 hover:text-slate-900 no-underline" href="{{ route('shop.products.index') }}">{{ __('shop::common.products.show.back') }}</a>
        @can('acl', 'product')
            <a
                href="{{ url('/admin/products/'.$product->getKey().'/edit') }}"
                class="inline-flex items-center gap-1 text-slate-500 hover:text-slate-700 no-underline"
                title="{{ __('shop::common.products.show.edit_title') }}"
            >
                <x-theme::icon name="pencil" class="w-5 h-5" />
            </a>
        @endcan
    </p>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            @livewire('shop.product-gallery', ['productId' => $product->id])

            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm mt-4">
                <div class="p-4">
                    <div class="font-bold text-blue-700">
                        {{ $product->getPrice() }}
                    </div>
                    <div class="mt-2 text-sm text-slate-700 flex items-center gap-2">
                        <span class="font-medium">Készlet:</span>
                        <span>{{ $stock ?? 0 }} @if($product->productUnit){{ $product->productUnit->name }}@endif</span>
                        @if(!empty($inStock) && $inStock)
                            <span class="text-emerald-600" title="Raktáron">
                                <x-theme::icon name="badge-check" class="w-5 h-5" />
                            </span>
                        @endif
                    </div>
                    <form method="post" action="{{ route('shop.cart.store') }}" class="mt-3 flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label class="text-sm text-slate-600" for="qty">{{ __('shop::common.products.show.qty') }}</label>
                        <input id="qty" name="quantity" type="number" min="1" value="1" class="w-20 border border-slate-300 rounded-md px-2 py-1">
                        <button type="submit" class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-3 py-2 rounded-md hover:bg-emerald-700">{{ __('shop::common.products.show.add_to_cart') }}</button>
                    </form>
                    @if(session('status'))
                        <div class="text-sm text-emerald-700 mt-2">{{ session('status') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">
            <h2 class="mt-0 text-lg font-semibold">{{ __('shop::common.products.show.details') }}</h2>
            <table class="w-full border-collapse text-sm">
                <tbody>
                <tr class="border-t border-slate-200"><th class="text-left bg-slate-50 p-2">{{ __('shop::common.products.show.sku') }}</th><td class="p-2">{{ $product->sku }}</td></tr>
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
