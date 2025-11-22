@extends('shop::layouts.app')

@section('title', 'Megrendeléseim')
@section('page_title', 'Megrendeléseim')

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if($orders->count() === 0)
        <div class="text-slate-600">Még nincs megrendelése.</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                <tr class="text-left text-slate-600 border-b">
                    <th class="py-2 pr-4">Kód</th>
                    <th class="py-2 pr-4">Státusz</th>
                    <th class="py-2 pr-4">Létrehozva</th>
                    <th class="py-2 pr-4">Műveletek</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr class="border-b hover:bg-slate-50">
                        <td class="py-2 pr-4 font-mono">{{ $order->code }}</td>
                        <td class="py-2 pr-4">{{ optional($order->orderStatus)->name }}</td>
                        <td class="py-2 pr-4">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="py-2 pr-4">
                            <a class="text-blue-700 hover:underline" href="{{ route('shop.orders.show', $order->code) }}">Részletek</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
