@extends('shop::layouts.app')

@section('title', 'Kosár – Molitor Shop')
@section('page_title', 'Kosár')
@section('page_subtitle')A kosarában lévő termékek összesítése @endsection

@section('content')
    @include('shop::components.checkout-steps', ['current' => 1])
    @if(session('status'))
        <div class="mb-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    @livewire('shop.cart')
@endsection
