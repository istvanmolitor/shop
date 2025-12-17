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
                    @php
                        $selectedShippingId = old('order_shipping_id', data_get($session,'order_shipping_id'));
                    @endphp
                    @foreach($shippingMethods as $method)
                        @php
                            $price = $method->getPrice()->exchangeDefault();
                        @endphp
                        <label class="flex items-start gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="order_shipping_id" value="{{ $method->id }}"
                                   class="mt-1 shipping-method-radio"
                                   data-shipping-type="{{ $method->type }}"
                                   data-shipping-id="{{ $method->id }}"
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

            {{-- Dynamic Shipping Type Forms --}}
            <div class="md:col-span-2" id="shipping-type-forms">
                @foreach($shippingMethods as $method)
                    @if($method->type)
                        @php
                            $shippingType = $shippingHandler->getShippingType($method->type);
                        @endphp
                        @if($shippingType)
                            <div class="shipping-type-form p-4 border rounded space-y-3"
                                 data-shipping-id="{{ $method->id }}"
                                 style="display: {{ $selectedShippingId == $method->id ? 'block' : 'none' }};">
                                <h3 class="font-semibold mb-2">{{ $shippingType->getLabel() }}</h3>
                                @php
                                    $sessionShippingData = data_get($session, 'shipping_data', []);
                                    $oldShippingData = old('shipping_data', $sessionShippingData);
                                    $viewData = $shippingType->prepare($oldShippingData);
                                @endphp
                                {!! $shippingType->view($viewData) !!}
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('shop.cart.index') }}" class="text-gray-600">{{ __('shop::common.checkout.shipping.back_to_cart') }}</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">{{ __('shop::common.checkout.shipping.continue_to_payment') }}</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.shipping-method-radio');
            const forms = document.querySelectorAll('.shipping-type-form');

            function updateVisibleForm() {
                const selectedRadio = document.querySelector('.shipping-method-radio:checked');
                if (!selectedRadio) return;

                const selectedShippingId = selectedRadio.dataset.shippingId;

                forms.forEach(form => {
                    if (form.dataset.shippingId === selectedShippingId) {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                });
            }

            radios.forEach(radio => {
                radio.addEventListener('change', updateVisibleForm);
            });

            // Initialize on page load
            updateVisibleForm();
        });
    </script>

@endsection
