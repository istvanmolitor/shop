@extends('shop::layouts.app')

@section('title', 'Fizetési mód')
@section('page_title', 'Fizetési mód')
@section('page_subtitle')Válassza ki a fizetési módot.@endsection

@section('content')
    <x-shop::checkout-steps current="payment" />

    <form action="{{ route('shop.checkout.payment.store') }}" method="post" class="mt-6">
        @csrf
        <div class="max-w-2xl mx-auto">
            <h3 class="font-semibold mb-4">Fizetési mód</h3>
            <div class="space-y-4">
                @php($selectedPaymentId = old('order_payment_id', data_get($session,'order_payment_id')))
                @foreach(($paymentMethods ?? []) as $method)
                    @php($price = $method->getPrice()->exchangeDefault())
                    <label class="block p-4 border rounded cursor-pointer hover:bg-gray-50 transition @if($selectedPaymentId == $method->id) border-emerald-600 bg-emerald-50 @endif">
                        <div class="flex items-start gap-3">
                            <input type="radio" name="order_payment_id" value="{{ $method->id }}" class="mt-1 w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500" @checked($selectedPaymentId == $method->id)>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-lg">{{ $method->name }}</span>
                                    <span class="font-semibold text-gray-900">{{ $price }}</span>
                                </div>
                                @if($method->description)
                                    <div class="text-sm text-gray-600">{!! $method->description !!}</div>
                                @endif
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('order_payment_id')
                <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('shop.checkout.shipping') }}" class="text-gray-600 hover:text-gray-900">← Vissza az 1. lépéshez</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">Tovább a számlázáshoz →</button>
        </div>
    </form>
@endsection

