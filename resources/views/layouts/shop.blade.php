<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Webshop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 text-slate-900">
@include('shop::layouts.includes.header')
<div class="w-full px-4">
    <div class="py-8">
        <h1 class="m-0 text-2xl font-semibold">@yield('page_title', 'Webshop')</h1>
        @hasSection('page_subtitle')
            <p class="mt-1 text-slate-500">@yield('page_subtitle')</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
        <!-- Left sidebar: Product categories -->
        <aside class="lg:col-span-1">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="p-4 sm:p-5 lg:p-6">
                    <h2 class="text-base font-semibold text-slate-900 mb-3">Kategóriák</h2>
                    @if(isset($shopCategories) && $shopCategories->isNotEmpty())
                        <ul class="space-y-1">
                            @foreach($shopCategories as $cat)
                                <li>
                                    <span class="block px-2 py-1.5 rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">{{ $cat->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-slate-500 text-sm">Nincsenek kategóriák.</div>
                    @endif
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <main class="lg:col-span-3">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="p-4 sm:p-5 lg:p-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <div class="text-center text-slate-500 text-sm py-8">© {{ date('Y') }} Molitor Shop</div>
</div>
@livewireScripts
@stack('scripts')
</body>
</html>
