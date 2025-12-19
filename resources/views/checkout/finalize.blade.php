@extends('shop::layouts.app')

@section('title', 'Megrendelés – Véglegesítés')
@section('page_title', 'Megrendelés – 4/4: Véglegesítés')
@section('page_subtitle')Ellenőrizze az adatait, majd küldje el a megrendelést.@endsection

@section('content')
    <x-shop::checkout-steps current="finalize" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
            <h3 class="font-semibold mb-2">Számlázási adatok</h3>
            <div class="p-4 border rounded text-sm space-y-1">
                <div><span class="text-gray-500">Név:</span> {{ data_get($data,'invoice.name') }}</div>
                <div><span class="text-gray-500">Ország ID:</span> {{ data_get($data,'invoice.country_id') }}</div>
                <div><span class="text-gray-500">Irányítószám:</span> {{ data_get($data,'invoice.zip_code') }}</div>
                <div><span class="text-gray-500">Város:</span> {{ data_get($data,'invoice.city') }}</div>
                <div><span class="text-gray-500">Cím:</span> {{ data_get($data,'invoice.address') }}</div>
                @if(data_get($data,'invoice_same_as_shipping'))
                    <div class="text-gray-500">(Megegyezik a szállítási címmel)</div>
                @endif
            </div>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Szállítási adatok</h3>
            <div class="p-4 border rounded text-sm space-y-1">
                <div><span class="text-gray-500">Név:</span> {{ data_get($data,'shipping.name') }}</div>
                <div><span class="text-gray-500">Ország ID:</span> {{ data_get($data,'shipping.country_id') }}</div>
                <div><span class="text-gray-500">Irányítószám:</span> {{ data_get($data,'shipping.zip_code') }}</div>
                <div><span class="text-gray-500">Város:</span> {{ data_get($data,'shipping.city') }}</div>
                <div><span class="text-gray-500">Cím:</span> {{ data_get($data,'shipping.address') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
            <h3 class="font-semibold mb-2">Szállítási mód</h3>
            <div class="p-4 border rounded text-sm">{{ $shippingLabel }}</div>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Fizetési mód</h3>
            <div class="p-4 border rounded text-sm">{{ $paymentLabel }}</div>
        </div>
    </div>

    @if(isset($shippingTypeView))
    <div class="mt-6">
        <h3 class="font-semibold mb-2">Szállítási adatok</h3>
        <div class="p-4 border rounded text-sm">
            {!! $shippingTypeView !!}
        </div>
    </div>
    @endif

    <form action="{{ route('shop.checkout.place') }}" method="post" class="mt-6">
        @csrf
        <div class="mt-2">
            <label for="comment" class="block text-sm font-medium text-gray-700">Megjegyzés</label>
            <textarea id="comment" name="comment" rows="3" class="mt-1 block w-full border rounded p-2">{{ old('comment', data_get($data,'comment')) }}</textarea>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('shop.checkout.invoice') }}" class="text-gray-600">Vissza a 3. lépéshez</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">Megrendelés elküldése</button>
        </div>
    </form>
@endsection
