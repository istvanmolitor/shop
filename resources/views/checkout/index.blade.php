@extends('shop::layouts.shop')

@section('title', 'Megrendelés – Molitor Shop')
@section('page_title', 'Megrendelés')
@section('page_subtitle')Kérjük, ellenőrizze az adatait, majd küldje el a megrendelést.@endsection

@section('content')
    @if ($errors->any())
        <div class="mb-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold mb-2">Számlázási adatok</h3>
            <div class="p-4 border rounded">
                @if($invoiceAddress)
                    <div>{{ $customer->name }}</div>
                    <div>{{ $invoiceAddress->country?->name }} {{ $invoiceAddress->zip }} {{ $invoiceAddress->city }}</div>
                    <div>{{ $invoiceAddress->street }}</div>
                    @if($invoiceAddress->tax_number)
                        <div>Adószám: {{ $invoiceAddress->tax_number }}</div>
                    @endif
                @else
                    <div class="text-sm text-gray-500">Nincs megadva számlázási cím.</div>
                @endif
            </div>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Szállítási adatok</h3>
            <div class="p-4 border rounded">
                @if($shippingAddress)
                    <div>{{ $customer->name }}</div>
                    <div>{{ $shippingAddress->country?->name }} {{ $shippingAddress->zip }} {{ $shippingAddress->city }}</div>
                    <div>{{ $shippingAddress->street }}</div>
                @else
                    <div class="text-sm text-gray-500">Nincs megadva szállítási cím.</div>
                @endif
            </div>
        </div>
    </div>

    <form action="{{ route('shop.checkout.store') }}" method="post" class="mt-6">
        @csrf
        <div class="mb-4">
            <label for="comment" class="block text-sm font-medium text-gray-700">Megjegyzés</label>
            <textarea id="comment" name="comment" rows="3" class="mt-1 block w-full border rounded p-2">{{ old('comment') }}</textarea>
        </div>
        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">Megrendelés elküldése</button>
    </form>
@endsection
