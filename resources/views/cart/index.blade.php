@extends('shop::layouts.shop')

@section('title', 'Kosár – Molitor Shop')
@section('page_title', 'Kosár')
@section('page_subtitle')A kosarában lévő termékek összesítése @endsection

@section('content')
    @if(session('status'))
        <div class="mb-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    @livewire(\Molitor\Shop\Http\Livewire\CartComponent::class)
@endsection
