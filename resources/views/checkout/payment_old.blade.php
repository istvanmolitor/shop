@extends('shop::layouts.app')

@section('title', 'Megrendelés – Számlázás és fizetés')
@section('page_title', 'Megrendelés – 2/3: Fizetés')
@section('page_subtitle')Válassza ki a fizetési módot, majd adja meg a számlázási adatait.@endsection

@section('content')
    <x-shop::checkout-steps current="payment" />
    @if ($errors->any())
        <div class="mb-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('shop.checkout.payment.store') }}" method="post" class="mt-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-4">Fizetési mód</h3>
                <div class="space-y-4">
                    @php($selectedPaymentId = old('order_payment_id', data_get($session,'order_payment_id')))
                    @foreach(($paymentMethods ?? []) as $method)
                        @php($price = $method->getPrice()->exchangeDefault())
                        <label class="block p-4 border rounded cursor-pointer hover:bg-gray-50 transition @if($selectedPaymentId == $method->id) border-emerald-600 bg-emerald-50 @endif">
                            <input type="radio" name="order_payment_id" value="{{ $method->id }}" class="hidden" @checked($selectedPaymentId == $method->id) required>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-lg">{{ $method->name }}</span>
                                <span class="font-semibold text-gray-900">{{ $price }}</span>
                            </div>
                            @if($method->description)
                                <div class="text-sm text-gray-600">{!! $method->description !!}</div>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Számlázási adatok</h3>
                <div class="p-4 border rounded space-y-3">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="billing_same_as_shipping" value="1" @checked(old('billing_same_as_shipping', data_get($session,'billing_same_as_shipping')))>
                        <span>Megegyezik a szállítási címmel</span>
                    </label>
                    <div class="billing-fields space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Név</label>
                            <input type="text" name="billing[name]" value="{{ old('billing.name', data_get($session,'billing.name', $invoiceAddress?->name ?? $customer?->name)) }}" class="mt-1 block w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ország</label>
                            <select name="billing[country_id]" class="mt-1 block w-full border rounded p-2">
                                <option value="">– Válasszon –</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('billing.country_id', data_get($session,'billing.country_id', $invoiceAddress?->country_id)) == $country->id)>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Irányítószám</label>
                                <input type="text" name="billing[zip_code]" value="{{ old('billing.zip_code', data_get($session,'billing.zip_code', $invoiceAddress?->zip_code)) }}" class="mt-1 block w-full border rounded p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Város</label>
                                <input type="text" name="billing[city]" value="{{ old('billing.city', data_get($session,'billing.city', $invoiceAddress?->city)) }}" class="mt-1 block w-full border rounded p-2">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cím</label>
                            <input type="text" name="billing[address]" value="{{ old('billing.address', data_get($session,'billing.address', $invoiceAddress?->address)) }}" class="mt-1 block w-full border rounded p-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('shop.checkout.shipping') }}" class="text-gray-600">Vissza az 1. lépéshez</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">Tovább a véglegesítéshez</button>
        </div>
    </form>
    <script>
        (function(){
            // Handle billing address toggle
            const cb = document.querySelector('input[name="billing_same_as_shipping"]');
            const box = document.querySelector('.billing-fields');
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

            // Handle payment method selection styling
            const paymentRadios = document.querySelectorAll('input[name="order_payment_id"]');
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    paymentRadios.forEach(r => {
                        const label = r.closest('label');
                        if(label) {
                            label.classList.remove('border-emerald-600', 'bg-emerald-50');
                            label.classList.add('border-gray-300');
                        }
                    });
                    const selectedLabel = this.closest('label');
                    if(selectedLabel) {
                        selectedLabel.classList.remove('border-gray-300');
                        selectedLabel.classList.add('border-emerald-600', 'bg-emerald-50');
                    }
                });
            });
        })();
    </script>
@endsection
