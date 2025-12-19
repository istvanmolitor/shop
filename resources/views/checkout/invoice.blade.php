@extends('shop::layouts.app')

@section('title', 'Megrendelés – Számlázási cím')
@section('page_title', 'Megrendelés – 3/4: Számlázási adatok')
@section('page_subtitle')Adja meg a számlázási adatait.@endsection

@section('content')
    <x-shop::checkout-steps current="invoice" />
    @if ($errors->any())
        <div class="mb-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('shop.checkout.invoice.store') }}" method="post" class="mt-6">
        @csrf
        <div class="max-w-2xl mx-auto">
            <h3 class="font-semibold mb-4">Számlázási adatok</h3>
            <div class="p-6 border rounded-lg space-y-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="invoice_same_as_shipping" value="1" @checked(old('invoice_same_as_shipping', data_get($session,'invoice_same_as_shipping'))) class="rounded border-gray-300">
                    <span>Megegyezik a szállítási címmel</span>
                </label>

                <div class="invoice-fields space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Név *</label>
                        <input type="text" name="invoice[name]" value="{{ old('invoice.name', data_get($session,'invoice.name', $invoiceAddress?->name ?? $customer?->name)) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ország *</label>
                        <select name="invoice[country_id]" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <option value="">– Válasszon országot –</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('invoice.country_id', data_get($session,'invoice.country_id', $invoiceAddress?->country_id)) == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Irányítószám *</label>
                            <input type="text" name="invoice[zip_code]" value="{{ old('invoice.zip_code', data_get($session,'invoice.zip_code', $invoiceAddress?->zip_code)) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Város *</label>
                            <input type="text" name="invoice[city]" value="{{ old('invoice.city', data_get($session,'invoice.city', $invoiceAddress?->city)) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cím (utca, házszám) *</label>
                        <input type="text" name="invoice[address]" value="{{ old('invoice.address', data_get($session,'invoice.address', $invoiceAddress?->address)) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('shop.checkout.payment') }}" class="text-gray-600 hover:text-gray-900">← Vissza a 2. lépéshez</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">Tovább a véglegesítéshez →</button>
        </div>
    </form>

    <script>
        (function(){
            // Handle invoice address toggle
            const cb = document.querySelector('input[name="invoice_same_as_shipping"]');
            const box = document.querySelector('.invoice-fields');
            const inputs = box ? box.querySelectorAll('input, select') : [];

            function toggle(){
                if(!cb || !box) return;
                if(cb.checked){
                    box.classList.add('opacity-50','pointer-events-none');
                    inputs.forEach(input => input.removeAttribute('required'));
                }else{
                    box.classList.remove('opacity-50','pointer-events-none');
                    inputs.forEach(input => {
                        if(input.name.startsWith('invoice[')) {
                            input.setAttribute('required', 'required');
                        }
                    });
                }
            }

            if(cb){
                cb.addEventListener('change', toggle);
                toggle();
            }
        })();
    </script>
@endsection

