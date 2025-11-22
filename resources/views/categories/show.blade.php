@extends('shop::layouts.shop')

@section('title', $category->name . ' – Molitor Shop')
@section('page_title', $category->name)
@section('page_subtitle')Tekintse meg a(z) {{ $category->name }} kategóriában elérhető termékeket @endsection

@section('content')
    @livewire('molitor.shop.http.livewire.products-list-component', ['categoryId' => $category->id])
@endsection
