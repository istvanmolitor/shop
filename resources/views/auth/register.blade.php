@extends('shop::layouts.app')

@section('title', 'Regisztráció – Molitor Shop')
@section('page_title', 'Regisztráció')
@section('page_subtitle', 'Hozzon létre egy új vásárlói fiókot')

@section('content')
    <form method="POST" action="{{ route('shop.register.post') }}" class="mx-auto max-w-4xl space-y-6">
        @csrf
        <!-- Cards container -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Account section -->
            <section class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
                <h3 class="text-base font-semibold text-slate-900 mb-4">Fiók adatok</h3>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Név</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                        @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail cím</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                        @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Jelszó</label>
                            <input id="password" name="password" type="password" required class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Jelszó megerősítése</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Customer section -->
            <section class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
                <h3 class="text-base font-semibold text-slate-900 mb-4">Ügyfél adatok</h3>

                <div class="grid grid-cols-1 gap-4">
                    <!-- Company registration toggle -->
                    <div class="flex items-start gap-3">
                        <input id="is_company" name="is_company" type="checkbox" value="1" @checked(old('is_company')) class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="is_company" class="text-sm text-slate-700 cursor-pointer">
                            <span class="font-medium">Regisztráció cégként</span>
                            <span class="block text-slate-500">Ha cégként regisztrál, adja meg az ügyfél nevét és az adószámot.</span>
                        </label>
                    </div>

                    <!-- Company-only fields -->
                    <div id="companyFields" class="{{ old('is_company') ? '' : 'hidden' }} grid grid-cols-1 gap-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-1">Ügyfél neve</label>
                            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', old('name')) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="tax_number" class="block text-sm font-medium text-slate-700 mb-1">Adószám</label>
                            <input id="tax_number" name="tax_number" type="text" value="{{ old('tax_number') }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('tax_number')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <h4 class="text-sm font-medium text-slate-900 mb-2">Számlázási adatok</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="invoice_zip_code" class="block text-sm font-medium text-slate-700 mb-1">Irányítószám</label>
                                <input id="invoice_zip_code" name="invoice_zip_code" type="text" value="{{ old('invoice_zip_code') }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('invoice_zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="invoice_city" class="block text-sm font-medium text-slate-700 mb-1">Város</label>
                                <input id="invoice_city" name="invoice_city" type="text" value="{{ old('invoice_city') }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('invoice_city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="invoice_address" class="block text-sm font-medium text-slate-700 mb-1">Cím</label>
                            <input id="invoice_address" name="invoice_address" type="text" value="{{ old('invoice_address') }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('invoice_address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <h4 class="text-sm font-medium text-slate-900 mb-2">Szállítási adatok</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_zip_code" class="block text-sm font-medium text-slate-700 mb-1">Irányítószám</label>
                                <input id="shipping_zip_code" name="shipping_zip_code" type="text" value="{{ old('shipping_zip_code') }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('shipping_zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-slate-700 mb-1">Város</label>
                                <input id="shipping_city" name="shipping_city" type="text" value="{{ old('shipping_city') }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('shipping_city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="shipping_address" class="block text-sm font-medium text-slate-700 mb-1">Cím</label>
                            <input id="shipping_address" name="shipping_address" type="text" value="{{ old('shipping_address') }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('shipping_address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="flex items-center justify-between gap-4 pt-2">
            <a href="{{ route('shop.login') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Már van fiókja? Bejelentkezés</a>
            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-md shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Regisztráció</button>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isCompany = document.getElementById('is_company');
            const companyFields = document.getElementById('companyFields');
            const customerName = document.getElementById('customer_name');
            const taxNumber = document.getElementById('tax_number');
            const nameInput = document.getElementById('name');

            function updateCompanyVisibility() {
                const checked = isCompany.checked;
                if (checked) {
                    companyFields.classList.remove('hidden');
                    // Prefill customer_name from user name if empty
                    if (customerName && !customerName.value && nameInput) {
                        customerName.value = nameInput.value || '';
                    }
                } else {
                    companyFields.classList.add('hidden');
                    // Clear values so backend falls back to user name and ignores tax number
                    if (customerName) customerName.value = '';
                    if (taxNumber) taxNumber.value = '';
                }
            }

            if (isCompany && companyFields) {
                isCompany.addEventListener('change', updateCompanyVisibility);
                updateCompanyVisibility();
            }
        });
    </script>
@endsection
