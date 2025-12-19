@extends('shop::layouts.app')

@section('title', __('shop::common.orders.page_title', ['code' => $order->code]))
@section('page_title', __('shop::common.orders.page_title', ['code' => $order->code]))
@section('page_subtitle', __('shop::common.orders.status_label') . ' ' . (optional($order->orderStatus)->name ?? '-'))

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('shop.orders.index') }}" class="text-blue-700 hover:underline">{{ __('shop::common.orders.back') }}</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-2">{{ __('shop::common.orders.address.invoice') }}</h3>
                <div class="p-4 border rounded text-sm space-y-1">
                    @if($order->invoiceAddress)
                        <div><span class="text-gray-500">{{ __('shop::common.checkout.name') }}:</span> {{ $order->invoiceAddress->name }}</div>
                        @if($order->invoiceAddress->country)
                            <div><span class="text-gray-500">{{ __('shop::common.checkout.country') }}:</span> {{ $order->invoiceAddress->country->name }}</div>
                        @endif
                        <div><span class="text-gray-500">{{ __('shop::common.checkout.finalize.zip_code') }}:</span> {{ $order->invoiceAddress->zip_code }}</div>
                        <div><span class="text-gray-500">{{ __('shop::common.checkout.finalize.city') }}:</span> {{ $order->invoiceAddress->city }}</div>
                        <div><span class="text-gray-500">{{ __('shop::common.checkout.finalize.address') }}:</span> {{ $order->invoiceAddress->address }}</div>
                    @else
                        <div class="text-slate-500">{{ __('shop::common.orders.details.no_value') }}</div>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">{{ __('shop::common.orders.shipping_method') }}</h3>
                <div class="p-4 border rounded text-sm space-y-2">
                    @if($order->orderShipping)
                        <div class="font-medium">{{ $order->orderShipping->name }}</div>
                        @if($order->shippingAddress)
                            <div class="mt-2 pt-2 border-t">
                                <div>{{ $order->shippingAddress->name }}</div>
                                <div>{{ $order->shippingAddress->zip_code }} {{ $order->shippingAddress->city }}</div>
                                <div>{{ $order->shippingAddress->address }}</div>
                            </div>
                        @endif
                    @else
                        <div class="text-slate-500">{{ __('shop::common.orders.details.no_value') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-semibold mb-2">{{ __('shop::common.orders.payment_method') }}</h3>
            <div class="p-4 border rounded text-sm">
                @if($order->orderPayment)
                    {{ $order->orderPayment->name }}
                @else
                    <span class="text-slate-500">{{ __('shop::common.orders.details.no_value') }}</span>
                @endif
            </div>
        </div>

        <div>
            <h3 class="font-semibold mb-2">{{ __('shop::common.orders.items.title') }}</h3>
            <div class="overflow-x-auto border rounded">
                @if($order->orderItems->isEmpty())
                    <div class="p-4 text-slate-500">{{ __('shop::common.orders.items.none') }}</div>
                @else
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-3 border-b border-slate-200">{{ __('shop::common.orders.items.product') }}</th>
                                <th class="text-right p-3 border-b border-slate-200">{{ __('shop::common.orders.items.unit_price') }}</th>
                                <th class="text-right p-3 border-b border-slate-200">{{ __('shop::common.orders.items.quantity') }}</th>
                                <th class="text-right p-3 border-b border-slate-200">{{ __('shop::common.orders.items.subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandTotal = 0;
                                $currencyCode = null;
                            @endphp
                            @foreach($order->orderItems as $item)
                                @php
                                    $product = $item->product;
                                    $unitPrice = $item->price / 100;
                                    $lineSubtotal = $unitPrice * $item->quantity;
                                    $grandTotal += $lineSubtotal;
                                    $currencyCode = $item->currency?->code ?? $currencyCode;
                                    $img = optional($product?->productImages->first());
                                    $imgUrl = $img?->getSrc();
                                @endphp
                                <tr class="border-t border-slate-200">
                                    <td class="p-3">
                                        <div class="flex items-center gap-3">
                                            @php($fallback = asset('vendor/shop/product/noimage.png'))
                                            @php($src = $imgUrl ?: $fallback)
                                            <img class="w-12 h-12 object-cover rounded-md border border-slate-200" src="{{ $src }}" alt="{{ $product?->name ?? '' }}">
                                            <div>
                                                <div class="font-medium text-slate-900">{{ $product?->name ?? ('#'.$item->product_id) }}</div>
                                                @if($product)
                                                    <div class="text-slate-500 text-xs">{{ __('shop::common.orders.sku') }}: {{ $product->sku }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 text-right whitespace-nowrap">{{ number_format($unitPrice, 2, ',', ' ') }} {{ $currencyCode }}</td>
                                    <td class="p-3 text-right">{{ $item->quantity }}</td>
                                    <td class="p-3 text-right whitespace-nowrap font-medium">{{ number_format($lineSubtotal, 2, ',', ' ') }} {{ $currencyCode }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 border-t border-slate-200">
                                <td class="p-3 font-semibold" colspan="3">{{ __('shop::common.orders.grand_total') }}</td>
                                <td class="p-3 text-right font-bold">{{ number_format($grandTotal, 2, ',', ' ') }} {{ $currencyCode }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        </div>

        @if($order->comment)
            <div>
                <h3 class="font-semibold mb-2">{{ __('shop::common.orders.note.title') }}</h3>
                <div class="p-4 border rounded text-sm text-slate-700">{{ $order->comment }}</div>
            </div>
        @endif
    </div>
@endsection
