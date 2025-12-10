@extends('shop::layouts.app')

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

    <form action="{{ route('shop.checkout.store') }}" method="post" class="mt-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-2">Számlázási adatok</h3>
                <div class="p-4 border rounded space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Név</label>
                        <input type="text" name="billing[name]" value="{{ old('billing.name', $invoiceAddress?->name ?? $customer?->name) }}" class="mt-1 block w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ország</label>
                        <select name="billing[country_id]" class="mt-1 block w-full border rounded p-2" required>
                            <option value="">– Válasszon –</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('billing.country_id', $invoiceAddress?->country_id) == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Irányítószám</label>
                            <input type="text" name="billing[zip_code]" value="{{ old('billing.zip_code', $invoiceAddress?->zip_code) }}" class="mt-1 block w-full border rounded p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Város</label>
                            <input type="text" name="billing[city]" value="{{ old('billing.city', $invoiceAddress?->city) }}" class="mt-1 block w-full border rounded p-2" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cím</label>
                        <input type="text" name="billing[address]" value="{{ old('billing.address', $invoiceAddress?->address) }}" class="mt-1 block w-full border rounded p-2" required>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Szállítási adatok</h3>
                <div class="p-4 border rounded space-y-3">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="shipping_same_as_billing" value="1" @checked(old('shipping_same_as_billing'))>
                        <span>Megegyezik a számlázási címmel</span>
                    </label>
                    <div class="shipping-fields space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Név</label>
                            <input type="text" name="shipping[name]" value="{{ old('shipping.name', $shippingAddress?->name ?? $customer?->name) }}" class="mt-1 block w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ország</label>
                            <select name="shipping[country_id]" class="mt-1 block w-full border rounded p-2">
                                <option value="">– Válasszon –</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('shipping.country_id', $shippingAddress?->country_id) == $country->id)>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Irányítószám</label>
                                <input type="text" name="shipping[zip_code]" value="{{ old('shipping.zip_code', $shippingAddress?->zip_code) }}" class="mt-1 block w-full border rounded p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Város</label>
                                <input type="text" name="shipping[city]" value="{{ old('shipping.city', $shippingAddress?->city) }}" class="mt-1 block w-full border rounded p-2">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cím</label>
                            <input type="text" name="shipping[address]" value="{{ old('shipping.address', $shippingAddress?->address) }}" class="mt-1 block w-full border rounded p-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <h3 class="font-semibold mb-2">Szállítási mód</h3>
                <div class="p-4 border rounded">
                    <select name="order_shipping_id" class="mt-1 block w-full border rounded p-2" required>
                        <option value="">– Válasszon –</option>
                        @foreach($shippingOptions as $id => $label)
                            <option value="{{ $id }}" @selected(old('order_shipping_id') == $id)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Fizetési mód</h3>
                <div class="p-4 border rounded">
                    <select name="order_payment_id" class="mt-1 block w-full border rounded p-2" required>
                        <option value="">– Válasszon –</option>
                        @foreach($paymentOptions as $id => $label)
                            <option value="{{ $id }}" @selected(old('order_payment_id') == $id)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <label for="comment" class="block text-sm font-medium text-gray-700">Megjegyzés</label>
            <textarea id="comment" name="comment" rows="3" class="mt-1 block w-full border rounded p-2">{{ old('comment') }}</textarea>
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">Megrendelés elküldése</button>
        </div>
    </form>
    <script>
        (function(){
            const cb = document.querySelector('input[name="shipping_same_as_billing"]');
            const box = document.querySelector('.shipping-fields');
            function toggle(){
                if(!cb || !box) return;
                if(cb.checked){
                    box.classList.add('opacity-50','pointer-events-none');
                }else{
                    box.classList.remove('opacity-50','pointer-events-none');
                }
            }
            if(cb){
                cb.addEventListener('change', toggle);
                toggle();
            }
        })();
    </script>
@endsection
