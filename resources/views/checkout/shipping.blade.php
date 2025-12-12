@extends('shop::layouts.app')

@section('title', __('shop::common.checkout.shipping.title'))
@section('page_title', __('shop::common.checkout.shipping.page_title'))
@section('page_subtitle'){{ __('shop::common.checkout.shipping.subtitle') }}@endsection

@section('content')
    @include('shop::components.checkout-steps', ['current' => 2])
    @if ($errors->any())
        <div class="mb-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('shop.checkout.shipping.store') }}" method="post" class="mt-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-2">{{ __('shop::common.checkout.shipping.method') }}</h3>
                <div class="p-4 border rounded space-y-3">
                    @php($selectedShippingId = old('order_shipping_id', data_get($session,'order_shipping_id')))
                    @foreach($shippingMethods as $method)
                        @php($price = $method->getPrice()->exchangeDefault())
                        <label class="flex items-start gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="order_shipping_id" value="{{ $method->id }}" class="mt-1"
                                   @checked($selectedShippingId == $method->id) required>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">{{ $method->name }}</span>
                                    <span class="font-semibold text-gray-900">{{ $price }}</span>
                                </div>
                                @if($method->description)
                                    <div class="text-sm text-gray-600 mt-1">{!! $method->description !!}</div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="md:col-span-2">
                <h3 class="font-semibold mb-2">{{ __('shop::common.checkout.shipping.data') }}</h3>
                <div class="p-4 border rounded space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('shop::common.checkout.name') }}</label>
                        <input type="text" name="shipping[name]" value="{{ old('shipping.name', data_get($session,'shipping.name', $shippingAddress?->name ?? $customer?->name)) }}" class="mt-1 block w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('shop::common.checkout.country') }}</label>
                        <select name="shipping[country_id]" class="mt-1 block w-full border rounded p-2" required>
                            <option value="">{{ __('shop::common.address.select_placeholder') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('shipping.country_id', data_get($session,'shipping.country_id', $shippingAddress?->country_id)) == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('shop::common.address.zip_code') }}</label>
                            <input type="text" name="shipping[zip_code]" value="{{ old('shipping.zip_code', data_get($session,'shipping.zip_code', $shippingAddress?->zip_code)) }}" class="mt-1 block w-full border rounded p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('shop::common.address.city') }}</label>
                            <input type="text" name="shipping[city]" value="{{ old('shipping.city', data_get($session,'shipping.city', $shippingAddress?->city)) }}" class="mt-1 block w-full border rounded p-2" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('shop::common.address.address') }}</label>
                        <input type="text" name="shipping[address]" value="{{ old('shipping.address', data_get($session,'shipping.address', $shippingAddress?->address)) }}" class="mt-1 block w-full border rounded p-2" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('shop.cart.index') }}" class="text-gray-600">{{ __('shop::common.checkout.shipping.back_to_cart') }}</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">{{ __('shop::common.checkout.shipping.continue_to_payment') }}</button>
        </div>
    </form>


@endsection
