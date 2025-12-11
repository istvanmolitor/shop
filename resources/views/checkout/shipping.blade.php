@extends('shop::layouts.app')

@section('title', 'Megrendelés – Szállítási adatok')
@section('page_title', 'Megrendelés – 1/3: Szállítás')
@section('page_subtitle')Adja meg a szállítási adatait és válassza ki a szállítási módot.@endsection

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
            <div class="md:col-span-2">
                <h3 class="font-semibold mb-2">Szállítási adatok</h3>
                <div class="p-4 border rounded space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Név</label>
                        <input type="text" name="shipping[name]" value="{{ old('shipping.name', data_get($session,'shipping.name', $shippingAddress?->name ?? $customer?->name)) }}" class="mt-1 block w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ország</label>
                        <select name="shipping[country_id]" class="mt-1 block w-full border rounded p-2" required>
                            <option value="">– Válasszon –</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('shipping.country_id', data_get($session,'shipping.country_id', $shippingAddress?->country_id)) == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Irányítószám</label>
                            <input type="text" name="shipping[zip_code]" value="{{ old('shipping.zip_code', data_get($session,'shipping.zip_code', $shippingAddress?->zip_code)) }}" class="mt-1 block w-full border rounded p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Város</label>
                            <input type="text" name="shipping[city]" value="{{ old('shipping.city', data_get($session,'shipping.city', $shippingAddress?->city)) }}" class="mt-1 block w-full border rounded p-2" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cím</label>
                        <input type="text" name="shipping[address]" value="{{ old('shipping.address', data_get($session,'shipping.address', $shippingAddress?->address)) }}" class="mt-1 block w-full border rounded p-2" required>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Szállítási mód</h3>
                <div class="p-4 border rounded">
                    <select name="order_shipping_id" class="mt-1 block w-full border rounded p-2" required>
                        <option value="">– Válasszon –</option>
                        @foreach($shippingOptions as $id => $label)
                            <option value="{{ $id }}" @selected(old('order_shipping_id', data_get($session,'order_shipping_id')) == $id)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('shop.cart.index') }}" class="text-gray-600">Vissza a kosárhoz</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">Tovább a fizetéshez</button>
        </div>
    </form>


@endsection
