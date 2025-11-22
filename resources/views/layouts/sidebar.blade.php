@extends('shop::layouts.base')

@section('body')
    @include('shop::layouts.includes.header')
    <div class="w-full px-4 mt-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            <aside class="lg:col-span-1">
                @yield('sidebar')
            </aside>
            <main class="lg:col-span-3">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="p-4 sm:p-5 lg:p-6">
                        @include('shop::layouts.includes.page-header')
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    @include('shop::layouts.includes.footer')
@endsection
