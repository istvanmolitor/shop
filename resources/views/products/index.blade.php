@extends('shop::layouts.shop')

@section('title', 'Termékek – Molitor Shop')
@section('page_title', 'Termékek')
@section('page_subtitle')Válogasson a legfrissebb termékeink közül @endsection

@section('content')
    @livewire('shop.products-list')
@endsection
