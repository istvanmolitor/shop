@extends('shop::layouts.base')

@section('body')
@include('shop::layouts.includes.header')
<div class="w-full px-4">
    <div class="py-8">
        <h1 class="m-0 text-2xl font-semibold">@yield('page_title', 'Webshop')</h1>
        @hasSection('page_subtitle')
            <p class="mt-1 text-slate-500">@yield('page_subtitle')</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
        @include('shop::layouts.includes.sidebar')
        <!-- Main content -->
        <main class="lg:col-span-3">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="p-4 sm:p-5 lg:p-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</div>
@include('shop::layouts.includes.footer')
@endsection
