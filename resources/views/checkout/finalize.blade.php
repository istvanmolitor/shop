@extends('shop::layouts.app')

@section('title', __('shop::common.checkout.finalize.title'))
@section('page_title', __('shop::common.checkout.finalize.page_title'))
@section('page_subtitle'){{ __('shop::common.checkout.finalize.subtitle') }}@endsection

@section('content')
    <x-shop::checkout-steps current="finalize" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
            <h3 class="font-semibold mb-2">{{ __('shop::common.checkout.finalize.billing_information') }}</h3>
            <div class="p-4 border rounded text-sm space-y-1">
                <div><span class="text-gray-500">{{ __('shop::common.checkout.name') }}:</span> {{ data_get($data,'invoice.name') }}</div>
                <div><span class="text-gray-500">{{ __('shop::common.checkout.country') }}:</span> {{ $countryName ?? data_get($data,'invoice.country_id') }}</div>
                <div><span class="text-gray-500">{{ __('shop::common.checkout.finalize.zip_code') }}:</span> {{ data_get($data,'invoice.zip_code') }}</div>
                <div><span class="text-gray-500">{{ __('shop::common.checkout.finalize.city') }}:</span> {{ data_get($data,'invoice.city') }}</div>
                <div><span class="text-gray-500">{{ __('shop::common.checkout.finalize.address') }}:</span> {{ data_get($data,'invoice.address') }}</div>
                @if(data_get($data,'invoice_same_as_shipping'))
                    <div class="text-gray-500">{{ __('shop::common.checkout.finalize.same_as_shipping') }}</div>
                @endif
            </div>
        </div>
        <div>
            <h3 class="font-semibold mb-2">{{ __('shop::common.checkout.finalize.shipping_method') }}</h3>
            <div class="p-4 border rounded text-sm space-y-2">
                <div class="font-medium">{{ $shippingLabel }}</div>
                <div>{!! $shippingTypeView !!}</div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div>
            <h3 class="font-semibold mb-2">{{ __('shop::common.checkout.finalize.payment_method') }}</h3>
            <div class="p-4 border rounded text-sm">{{ $paymentLabel }}</div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold mb-2">{{ __('shop::common.checkout.finalize.cart_contents') }}</h3>
        <div class="overflow-x-auto border rounded">
            @if($cartItems->isEmpty())
                <div class="p-4 text-slate-500">{{ __('shop::common.checkout.finalize.cart_empty') }}</div>
            @else
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left p-3 border-b border-slate-200">{{ __('shop::common.checkout.finalize.product') }}</th>
                            <th class="text-right p-3 border-b border-slate-200">{{ __('shop::common.checkout.finalize.unit_price') }}</th>
                            <th class="text-right p-3 border-b border-slate-200">{{ __('shop::common.checkout.finalize.quantity') }}</th>
                            <th class="text-right p-3 border-b border-slate-200">{{ __('shop::common.checkout.finalize.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            /** @var \Molitor\Currency\Services\Price $grandTotal */
                            $grandTotal = new \Molitor\Currency\Services\Price(0, null);
                        @endphp
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->product;
                                /** @var \Molitor\Currency\Services\Price $unitPrice */
                                $unitPrice = $product->getPrice();
                                $unitPriceDefault = $unitPrice->exchangeDefault();
                                $lineSubtotal = $unitPrice->multiple((int)$item->quantity)->exchangeDefault();
                                $grandTotal = $grandTotal->addition($lineSubtotal);
                                $img = optional($product->productImages->first());
                                $imgUrl = $img?->getSrc();
                            @endphp
                            <tr class="border-t border-slate-200">
                                <td class="p-3">
                                    <div class="flex items-center gap-3">
                                        @php($fallback = asset('vendor/shop/product/noimage.png'))
                                        @php($src = $imgUrl ?: $fallback)
                                        <img class="w-12 h-12 object-cover rounded-md border border-slate-200" src="{{ $src }}" alt="{{ $product->name }}">
                                        <div>
                                            <div class="font-medium text-slate-900">{{ $product->name }}</div>
                                            <div class="text-slate-500 text-xs">{{ __('shop::common.checkout.finalize.sku') }}: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 text-right whitespace-nowrap">{{ $unitPriceDefault }}</td>
                                <td class="p-3 text-right">{{ $item->quantity }}</td>
                                <td class="p-3 text-right whitespace-nowrap font-medium">{{ $lineSubtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 border-t border-slate-200">
                            <td class="p-3 font-semibold" colspan="3">{{ __('shop::common.checkout.finalize.grand_total') }}</td>
                            <td class="p-3 text-right font-bold">{{ $grandTotal->exchangeDefault() }}</td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>

    <form action="{{ route('shop.checkout.place') }}" method="post" class="mt-6">
        @csrf
        <div class="mt-2 mb-3">
            <label for="comment" class="block text-sm font-medium text-gray-700">{{ __('shop::common.checkout.finalize.comment') }}</label>
            <textarea id="comment" name="comment" rows="3" class="mt-1 block w-full border rounded p-2">{{ old('comment', data_get($data,'comment')) }}</textarea>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('shop.checkout.invoice') }}" class="text-gray-600">{{ __('shop::common.checkout.finalize.back_to_invoice') }}</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">{{ __('shop::common.checkout.finalize.submit_order') }}</button>
        </div>
    </form>
@endsection

