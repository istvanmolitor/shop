@extends('shop::layouts.app')

@section('title', 'Finalize Order')
@section('page_title', 'Finalize Order')
@section('page_subtitle')Please review your information and submit your order.@endsection

@section('content')
    <x-shop::checkout-steps current="finalize" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
            <h3 class="font-semibold mb-2">Billing Information</h3>
            <div class="p-4 border rounded text-sm space-y-1">
                <div><span class="text-gray-500">Name:</span> {{ data_get($data,'invoice.name') }}</div>
                <div><span class="text-gray-500">Country:</span> {{ $countryName ?? data_get($data,'invoice.country_id') }}</div>
                <div><span class="text-gray-500">Zip Code:</span> {{ data_get($data,'invoice.zip_code') }}</div>
                <div><span class="text-gray-500">City:</span> {{ data_get($data,'invoice.city') }}</div>
                <div><span class="text-gray-500">Address:</span> {{ data_get($data,'invoice.address') }}</div>
                @if(data_get($data,'invoice_same_as_shipping'))
                    <div class="text-gray-500">(Same as shipping address)</div>
                @endif
            </div>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Shipping Method</h3>
            <div class="p-4 border rounded text-sm space-y-2">
                <div class="font-medium">{{ $shippingLabel }}</div>
                <div>{!! $shippingTypeView !!}</div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div>
            <h3 class="font-semibold mb-2">Payment Method</h3>
            <div class="p-4 border rounded text-sm">{{ $paymentLabel }}</div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold mb-2">Cart Contents</h3>
        <div class="overflow-x-auto border rounded">
            @if($cartItems->isEmpty())
                <div class="p-4 text-slate-500">Your cart is empty.</div>
            @else
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left p-3 border-b border-slate-200">Product</th>
                            <th class="text-right p-3 border-b border-slate-200">Unit Price</th>
                            <th class="text-right p-3 border-b border-slate-200">Quantity</th>
                            <th class="text-right p-3 border-b border-slate-200">Subtotal</th>
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
                                            <div class="text-slate-500 text-xs">SKU: {{ $product->sku }}</div>
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
                            <td class="p-3 font-semibold" colspan="3">Grand Total</td>
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
            <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
            <textarea id="comment" name="comment" rows="3" class="mt-1 block w-full border rounded p-2">{{ old('comment', data_get($data,'comment')) }}</textarea>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('shop.checkout.invoice') }}" class="text-gray-600">Back to step 3</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">Submit Order</button>
        </div>
    </form>
@endsection
