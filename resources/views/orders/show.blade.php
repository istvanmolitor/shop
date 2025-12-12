@extends('shop::layouts.app')

@section('title', __('shop::common.orders.page_title', ['code' => $order->code]))
@section('page_title', __('shop::common.orders.page_title', ['code' => $order->code]))
@section('page_subtitle', __('shop::common.orders.status_label') . ' ' . (optional($order->orderStatus)->name ?? '-'))

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('shop.orders.index') }}" class="text-blue-700 hover:underline">{{ __('shop::common.orders.back') }}</a>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="rounded-lg border border-slate-200 p-4">
                <h3 class="font-semibold mb-3">{{ __('shop::common.orders.address.billing') }}</h3>
                @if($order->invoiceAddress)
                    <div class="text-sm text-slate-700">
                        <div>{{ $order->invoiceAddress->name }}</div>
                        <div>{{ $order->invoiceAddress->zip_code }} {{ $order->invoiceAddress->city }}</div>
                        <div>{{ $order->invoiceAddress->address }}</div>
                    </div>
                @else
                    <div class="text-slate-500 text-sm">{{ __('shop::common.orders.details.no_value') }}</div>
                @endif
            </div>
            <div class="rounded-lg border border-slate-200 p-4">
                <h3 class="font-semibold mb-3">{{ __('shop::common.orders.address.shipping') }}</h3>
                @if($order->shippingAddress)
                    <div class="text-sm text-slate-700">
                        <div>{{ $order->shippingAddress->name }}</div>
                        <div>{{ $order->shippingAddress->zip_code }} {{ $order->shippingAddress->city }}</div>
                        <div>{{ $order->shippingAddress->address }}</div>
                    </div>
                @else
                    <div class="text-slate-500 text-sm">{{ __('shop::common.orders.details.no_value') }}</div>
                @endif
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 p-4">
            <h3 class="font-semibold mb-3">{{ __('shop::common.orders.items.title') }}</h3>
            @if($order->orderItems->isEmpty())
                <div class="text-slate-500 text-sm">{{ __('shop::common.orders.items.none') }}</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="text-left text-slate-600 border-b">
                            <th class="py-2 pr-4">{{ __('shop::common.orders.items.product') }}</th>
                            <th class="py-2 pr-4">{{ __('shop::common.orders.items.qty') }}</th>
                            <th class="py-2 pr-4">{{ __('shop::common.orders.items.unit_price') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->orderItems as $item)
                            <tr class="border-b">
                                <td class="py-2 pr-4">{{ $item->product?->name ?? ('#'.$item->product_id) }}</td>
                                <td class="py-2 pr-4">{{ $item->quantity }}</td>
                                <td class="py-2 pr-4">{{ number_format($item->price / 100, 2, ',', ' ') }} {{ $item->currency?->code }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($order->comment)
            <div class="rounded-lg border border-slate-200 p-4">
                <h3 class="font-semibold mb-2">{{ __('shop::common.orders.note.title') }}</h3>
                <div class="text-sm text-slate-700">{{ $order->comment }}</div>
            </div>
        @endif
    </div>
@endsection
