@extends('shop::layouts.app')

@section('title', __('shop::common.profile.title'))
@section('page_title', __('shop::common.profile.page_title'))
@section('page_subtitle', __('shop::common.profile.subtitle'))

@section('content')
    @if(session('status'))
        <div class="mb-4 rounded-md bg-emerald-50 p-3 text-sm text-emerald-700 border border-emerald-200">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('shop.profile.update') }}" class="mx-auto max-w-4xl space-y-6">
        @csrf
        <!-- Cards container -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Account section -->
            <section class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
                <h3 class="text-base font-semibold text-slate-900 mb-4">{{ __('shop::common.profile.user_data') }}</h3>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.checkout.name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                        @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.auth.login.email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                        @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <!-- Customer section -->
            <section class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
                <h3 class="text-base font-semibold text-slate-900 mb-4">{{ __('shop::common.profile.customer_data') }}</h3>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.auth.register.customer_name') }}</label>
                        <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', optional($customer)->name) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                        @error('customer_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="pt-2">
                        <h4 class="text-sm font-medium text-slate-900 mb-2">{{ __('shop::common.profile.invoice_title') }}</h4>
                        <div class="mb-4">
                            <label for="invoice_name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.name') }}</label>
                            <input id="invoice_name" name="invoice[name]" type="text" value="{{ old('invoice.name', optional(optional($customer)->invoiceAddress)->name) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('invoice.name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="invoice_country_id" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.country') }}</label>
                            <select id="invoice_country_id" name="invoice[country_id]" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                <option value="">{{ __('shop::common.address.select_placeholder') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('invoice.country_id', optional(optional($customer)->invoiceAddress)->country_id) == $country->id)>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('invoice.country_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="invoice_zip_code" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.zip_code') }}</label>
                                <input id="invoice_zip_code" name="invoice[zip_code]" type="text" value="{{ old('invoice.zip_code', optional(optional($customer)->invoiceAddress)->zip_code) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('invoice.zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="invoice_city" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.city') }}</label>
                                <input id="invoice_city" name="invoice[city]" type="text" value="{{ old('invoice.city', optional(optional($customer)->invoiceAddress)->city) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('invoice.city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="invoice_address" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.address') }}</label>
                            <input id="invoice_address" name="invoice[address]" type="text" value="{{ old('invoice.address', optional(optional($customer)->invoiceAddress)->address) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('invoice.address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <h4 class="text-sm font-medium text-slate-900 mb-2">{{ __('shop::common.profile.shipping_title') }}</h4>
                        <div class="mb-4">
                            <label for="shipping_name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.name') }}</label>
                            <input id="shipping_name" name="shipping[name]" type="text" value="{{ old('shipping.name', optional(optional($customer)->shippingAddress)->name) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('shipping.name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="shipping_country_id" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.country') }}</label>
                            <select id="shipping_country_id" name="shipping[country_id]" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                <option value="">{{ __('shop::common.address.select_placeholder') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('shipping.country_id', optional(optional($customer)->shippingAddress)->country_id) == $country->id)>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('shipping.country_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_zip_code" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.zip_code') }}</label>
                                <input id="shipping_zip_code" name="shipping[zip_code]" type="text" value="{{ old('shipping.zip_code', optional(optional($customer)->shippingAddress)->zip_code) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('shipping.zip_code')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.city') }}</label>
                                <input id="shipping_city" name="shipping[city]" type="text" value="{{ old('shipping.city', optional(optional($customer)->shippingAddress)->city) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                                @error('shipping.city')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="shipping_address" class="block text-sm font-medium text-slate-700 mb-1">{{ __('shop::common.address.address') }}</label>
                            <input id="shipping_address" name="shipping[address]" type="text" value="{{ old('shipping.address', optional(optional($customer)->shippingAddress)->address) }}" class="w-full rounded-md border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2">
                            @error('shipping.address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="flex items-center justify-end gap-4 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-md shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">{{ __('shop::common.profile.save') }}</button>
        </div>
    </form>
@endsection
