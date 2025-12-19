@extends('shop::layouts.app')

@section('title', 'Megrendelés – Számlázási cím')
@section('page_title', 'Megrendelés – 3/4: Számlázási adatok')
@section('page_subtitle')Adja meg a számlázási adatait.@endsection

@section('content')
    <x-shop::checkout-steps current="invoice" />

    <form action="{{ route('shop.checkout.invoice.store') }}" method="post" class="mt-6">
        @csrf
        <div class="max-w-2xl mx-auto">
            <h3 class="font-semibold mb-4">Számlázási adatok</h3>
            <div class="p-6 border rounded-lg space-y-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="invoice_same_as_shipping" value="1" @checked(old('invoice_same_as_shipping', data_get($invoice,'invoice_same_as_shipping'))) class="rounded border-gray-300">
                    <span>Megegyezik a szállítási címmel</span>
                </label>

                <div class="invoice-fields space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Név *</label>
                        <input type="text" name="name" value="{{ old('name', data_get($invoice,'name')) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ország *</label>
                        <select name="country_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500 @error('country_id') border-red-500 @enderror">
                            <option value="">– Válasszon országot –</option>
                            @foreach($countries as $id => $country)
                                <option value="{{ $id }}" @selected(old('country_id', data_get($invoice, 'country_id')) == $id)>{{ $country }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Irányítószám *</label>
                            <input type="text" name="zip_code" value="{{ old('zip_code', data_get($invoice,'zip_code')) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500 @error('zip_code') border-red-500 @enderror">
                            @error('zip_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Város *</label>
                            <input type="text" name="city" value="{{ old('city', data_get($invoice,'city')) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500 @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cím (utca, házszám) *</label>
                        <input type="text" name="address" value="{{ old('address', data_get($invoice,'address')) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500 @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('shop.checkout.payment') }}" class="text-gray-600 hover:text-gray-900">← Vissza a 2. lépéshez</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">Tovább a véglegesítéshez →</button>
        </div>
    </form>
@endsection

