@extends('shop::layouts.app')

@section('title', __('shop::common.checkout.shipping.title'))
@section('page_title', __('shop::common.checkout.shipping.page_title'))
@section('page_subtitle'){{ __('shop::common.checkout.shipping.subtitle') }}@endsection

@section('content')
    @include('shop::components.checkout-steps', ['current' => 2])

    <div class="max-w-4xl mx-auto">
        @livewire('shop.shipping-method')
    </div>
@endsection
