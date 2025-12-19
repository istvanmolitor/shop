@extends('shop::layouts.app')

@section('title', 'Megrendelés – Fizetési mód')
@section('page_title', 'Megrendelés – 2/4: Fizetési mód')
@section('page_subtitle')Válassza ki a fizetési módot.@endsection

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
        <div class="max-w-2xl mx-auto">
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

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('shop.checkout.shipping') }}" class="text-gray-600 hover:text-gray-900">← Vissza az 1. lépéshez</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">Tovább a számlázáshoz →</button>
        </div>
    </form>
    <script>
        (function(){
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

