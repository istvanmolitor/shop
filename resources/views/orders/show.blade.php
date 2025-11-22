@extends('shop::layouts.shop')

@section('title', 'Megrendelés részletei')
@section('page_title', 'Megrendelés ' . $order->code)
@section('page_subtitle', 'Státusz: ' . (optional($order->orderStatus)->name ?? '-'))

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('shop.orders.index') }}" class="text-blue-700 hover:underline">← Vissza a megrendelésekhez</a>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="rounded-lg border border-slate-200 p-4">
                <h3 class="font-semibold mb-3">Számlázási cím</h3>
                @if($order->invoiceAddress)
                    <div class="text-sm text-slate-700">
                        <div>{{ $order->invoiceAddress->name }}</div>
                        <div>{{ $order->invoiceAddress->zip_code }} {{ $order->invoiceAddress->city }}</div>
                        <div>{{ $order->invoiceAddress->address }}</div>
                    </div>
                @else
                    <div class="text-slate-500 text-sm">Nincs megadva.</div>
                @endif
            </div>
            <div class="rounded-lg border border-slate-200 p-4">
                <h3 class="font-semibold mb-3">Szállítási cím</h3>
                @if($order->shippingAddress)
                    <div class="text-sm text-slate-700">
                        <div>{{ $order->shippingAddress->name }}</div>
                        <div>{{ $order->shippingAddress->zip_code }} {{ $order->shippingAddress->city }}</div>
                        <div>{{ $order->shippingAddress->address }}</div>
                    </div>
                @else
                    <div class="text-slate-500 text-sm">Nincs megadva.</div>
                @endif
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 p-4">
            <h3 class="font-semibold mb-3">Tételek</h3>
            @if($order->orderItems->isEmpty())
                <div class="text-slate-500 text-sm">Nincsenek tételek.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="text-left text-slate-600 border-b">
                            <th class="py-2 pr-4">Termék</th>
                            <th class="py-2 pr-4">Mennyiség</th>
                            <th class="py-2 pr-4">Egységár</th>
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
                <h3 class="font-semibold mb-2">Megjegyzés</h3>
                <div class="text-sm text-slate-700">{{ $order->comment }}</div>
            </div>
        @endif
    </div>
@endsection
