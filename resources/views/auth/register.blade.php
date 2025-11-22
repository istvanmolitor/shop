@extends('shop::layouts.shop')

@section('title', 'Regisztráció – Molitor Shop')
@section('page_title', 'Regisztráció')
@section('page_subtitle', 'Hozzon létre egy új vásárlói fiókot')

@section('content')
    <form method="POST" action="{{ route('shop.register.post') }}" class="max-w-md space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Név</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail cím</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Jelszó</label>
            <input id="password" name="password" type="password" required class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Jelszó megerősítése</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full border border-slate-300 rounded-md px-3 py-2">
        </div>

        <div class="pt-4 border-t border-slate-200">
            <h3 class="text-md font-semibold text-slate-800 mb-2">Ügyfél adatok</h3>
            <div class="mb-2">
                <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-1">Ügyfél neve</label>
                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', old('name')) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="invoice_zip_code" class="block text-sm font-medium text-slate-700 mb-1">Számlázási irányítószám</label>
                    <input id="invoice_zip_code" name="invoice_zip_code" type="text" value="{{ old('invoice_zip_code') }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('invoice_zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="invoice_city" class="block text-sm font-medium text-slate-700 mb-1">Számlázási város</label>
                    <input id="invoice_city" name="invoice_city" type="text" value="{{ old('invoice_city') }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('invoice_city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
            <div>
                <label for="invoice_address" class="block text-sm font-medium text-slate-700 mb-1">Számlázási cím</label>
                <input id="invoice_address" name="invoice_address" type="text" value="{{ old('invoice_address') }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                @error('invoice_address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="shipping_zip_code" class="block text-sm font-medium text-slate-700 mb-1">Szállítási irányítószám</label>
                    <input id="shipping_zip_code" name="shipping_zip_code" type="text" value="{{ old('shipping_zip_code') }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('shipping_zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="shipping_city" class="block text-sm font-medium text-slate-700 mb-1">Szállítási város</label>
                    <input id="shipping_city" name="shipping_city" type="text" value="{{ old('shipping_city') }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('shipping_city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
            <div>
                <label for="shipping_address" class="block text-sm font-medium text-slate-700 mb-1">Szállítási cím</label>
                <input id="shipping_address" name="shipping_address" type="text" value="{{ old('shipping_address') }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                @error('shipping_address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Regisztráció</button>
            <a href="{{ route('shop.login') }}" class="text-sm text-slate-700 hover:text-slate-900">Már van fiókja? Bejelentkezés</a>
        </div>
    </form>
@endsection
