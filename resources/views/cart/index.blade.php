@extends('shop::layouts.shop')

@section('title', 'Kosár – Molitor Shop')
@section('page_title', 'Kosár')
@section('page_subtitle')A kosarában lévő termékek összesítése @endsection

@section('content')
    @if(session('status'))
        <div class="mb-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    @if($items->isEmpty())
        <p class="text-slate-500">A kosara üres.</p>
        <p class="mt-3"><a class="inline-flex items-center gap-2 border border-blue-600 bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 no-underline" href="{{ route('shop.products.index') }}">Vissza a termékekhez</a></p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left p-3 border-b border-slate-200">Termék</th>
                        <th class="text-right p-3 border-b border-slate-200">Egységár</th>
                        <th class="text-right p-3 border-b border-slate-200">Mennyiség</th>
                        <th class="text-right p-3 border-b border-slate-200">Részösszeg</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $product = $item->product;
                            $price = (float)($product->price ?? 0);
                            $currency = $product->currency->code ?? '';
                            $subtotal = $price * (int)$item->quantity;
                            $img = optional($product->productImages->first());
                            $imgUrl = $img?->image_url ?: $img?->image;
                        @endphp
                        <tr class="border-t border-slate-200">
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @if($imgUrl)
                                        <img class="w-12 h-12 object-cover rounded-md border border-slate-200" src="{{ $imgUrl }}" alt="{{ $product->name }}">
                                    @endif
                                    <div>
                                        <a class="font-medium text-slate-900 hover:text-slate-950 no-underline" href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
                                        <div class="text-slate-500 text-xs">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">{{ number_format($price, 2, ',', ' ') }} {{ $currency }}</td>
                            <td class="p-3 text-right">{{ $item->quantity }}</td>
                            <td class="p-3 text-right whitespace-nowrap font-medium">{{ number_format($subtotal, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 border-t border-slate-200">
                        <td class="p-3" colspan="3"><span class="font-semibold">Végösszeg</span></td>
                        <td class="p-3 text-right font-bold">{{ number_format($total, 2, ',', ' ') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-4 flex items-center gap-3">
            <a class="inline-flex items-center gap-2 border border-slate-300 px-3 py-2 rounded-md hover:bg-slate-50 no-underline" href="{{ route('shop.products.index') }}">Vissza a termékekhez</a>
        </div>
    @endif
@endsection
