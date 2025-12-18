@extends('shop::layouts.app')

@section('title', __('shop::common.checkout.shipping.title'))
@section('page_title', __('shop::common.checkout.shipping.page_title'))
@section('page_subtitle'){{ __('shop::common.checkout.shipping.subtitle') }}@endsection

@section('content')
    @include('shop::components.checkout-steps', ['current' => 2])

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold mb-4">Válasszon szállítási módot</h3>
            <div class="space-y-4">
                @foreach($shippingMethods as $method)
                    <a href="{{ route('shop.checkout.shipping.show', ['shipping' => $method->code]) }}"
                       class="block p-4 border rounded hover:bg-gray-50 transition @if(isset($selectedShipping) && $selectedShipping->id == $method->id) border-emerald-600 bg-emerald-50 @endif">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-lg">{{ $method->name }}</span>
                            <span class="font-semibold text-gray-900">{{ $method->getPrice()->exchangeDefault() }}</span>
                        </div>
                        @if($method->description)
                            <div class="text-sm text-gray-600">{!! $method->description !!}</div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        <div>
            @if($selectedShipping)
                <h3 class="font-semibold mb-4">Szállítási adatok</h3>
                <div class="border rounded-lg p-6 bg-white shadow-sm">
                    {!! $shippingForm !!}
                </div>
            @endif
        </div>
    </div>
@endsection
