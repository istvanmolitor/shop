@extends('shop::layouts.app')

@section('title', __('shop::common.menu.orders'))
@section('page_title', __('shop::common.menu.orders'))

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if($orders->count() === 0)
        <div class="text-slate-600">{{ __('shop::common.orders.none') }}</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                <tr class="text-left text-slate-600 border-b">
                    <th class="py-2 pr-4">{{ __('shop::common.orders.table.code') }}</th>
                    <th class="py-2 pr-4">{{ __('shop::common.orders.table.status') }}</th>
                    <th class="py-2 pr-4">{{ __('shop::common.orders.table.created_at') }}</th>
                    <th class="py-2 pr-4">{{ __('shop::common.orders.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr class="border-b hover:bg-slate-50">
                        <td class="py-2 pr-4 font-mono">{{ $order->code }}</td>
                        <td class="py-2 pr-4">{{ optional($order->orderStatus)->name }}</td>
                        <td class="py-2 pr-4">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="py-2 pr-4">
                            <a class="text-blue-700 hover:underline" href="{{ route('shop.orders.show', $order->code) }}">{{ __('shop::common.orders.table.details') }}</a>
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
