@extends('shop::layouts.app')

@section('title', 'Kosár – Molitor Shop')
@section('page_title', 'Kosár')
@section('page_subtitle')A kosarában lévő termékek összesítése @endsection

@section('content')
    <x-shop::checkout-steps current="cart" />
    @if(session('status'))
        <div class="mb-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    @livewire('shop.cart')

    <div class="mt-4 flex items-center gap-3">
        <a class="inline-flex items-center gap-2 border border-slate-300 px-3 py-2 rounded-md hover:bg-slate-50 no-underline" href="{{ route('shop.products.index') }}">Vissza a termékekhez</a>
        @auth
            <a class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-3 py-2 rounded-md hover:bg-emerald-700 no-underline" href="{{ $shippingRoute }}">Tovább a megrendeléshez</a>
        @else
            <a class="inline-flex items-center gap-2 border border-emerald-600 text-emerald-700 px-3 py-2 rounded-md hover:bg-emerald-50 no-underline" href="{{ route('shop.login') }}">Bejelentkezés a megrendeléshez</a>
        @endauth
    </div>
@endsection
