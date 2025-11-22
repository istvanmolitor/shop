@extends('shop::layouts.app')

@section('title', 'Profil – Molitor Shop')
@section('page_title', 'Profil')
@section('page_subtitle', 'Felhasználói és ügyfél adatok szerkesztése')

@section('content')
    @if(session('status'))
        <div class="mb-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('shop.profile.update') }}" class="max-w-3xl space-y-6">
        @csrf

        <div class="space-y-4">
            <h3 class="text-md font-semibold text-slate-800">Felhasználói adatok</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Név</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail cím</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 space-y-4">
            <h3 class="text-md font-semibold text-slate-800">Ügyfél adatok</h3>
            <div>
                <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-1">Ügyfél neve</label>
                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', optional($customer)->name) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-slate-700">Számlázási cím</h4>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="invoice_name">Megszólítás/Név</label>
                        <input id="invoice_name" name="invoice[name]" type="text" value="{{ old('invoice.name', optional(optional($customer)->invoiceAddress)->name) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                        @error('invoice.name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="invoice_country_id">Ország</label>
                        <select id="invoice_country_id" name="invoice[country_id]" class="w-full border border-slate-300 rounded-md px-3 py-2">
                            <option value="">-- Válasszon --</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('invoice.country_id', optional(optional($customer)->invoiceAddress)->country_id) == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('invoice.country_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="invoice_zip_code">Irányítószám</label>
                            <input id="invoice_zip_code" name="invoice[zip_code]" type="text" value="{{ old('invoice.zip_code', optional(optional($customer)->invoiceAddress)->zip_code) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                            @error('invoice.zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="invoice_city">Város</label>
                            <input id="invoice_city" name="invoice[city]" type="text" value="{{ old('invoice.city', optional(optional($customer)->invoiceAddress)->city) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                            @error('invoice.city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="invoice_address">Cím</label>
                        <input id="invoice_address" name="invoice[address]" type="text" value="{{ old('invoice.address', optional(optional($customer)->invoiceAddress)->address) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                        @error('invoice.address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-slate-700">Szállítási cím</h4>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="shipping_name">Megszólítás/Név</label>
                        <input id="shipping_name" name="shipping[name]" type="text" value="{{ old('shipping.name', optional(optional($customer)->shippingAddress)->name) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                        @error('shipping.name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="shipping_country_id">Ország</label>
                        <select id="shipping_country_id" name="shipping[country_id]" class="w-full border border-slate-300 rounded-md px-3 py-2">
                            <option value="">-- Válasszon --</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('shipping.country_id', optional(optional($customer)->shippingAddress)->country_id) == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('shipping.country_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="shipping_zip_code">Irányítószám</label>
                            <input id="shipping_zip_code" name="shipping[zip_code]" type="text" value="{{ old('shipping.zip_code', optional(optional($customer)->shippingAddress)->zip_code) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                            @error('shipping.zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1" for="shipping_city">Város</label>
                            <input id="shipping_city" name="shipping[city]" type="text" value="{{ old('shipping.city', optional(optional($customer)->shippingAddress)->city) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                            @error('shipping.city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1" for="shipping_address">Cím</label>
                        <input id="shipping_address" name="shipping[address]" type="text" value="{{ old('shipping.address', optional(optional($customer)->shippingAddress)->address) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                        @error('shipping.address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Mentés</button>
        </div>
    </form>
@endsection
