<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Webshop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 text-slate-900">
<nav class="sticky top-0 z-10 border-b border-slate-200 bg-white/70 backdrop-blur supports-[backdrop-filter]:bg-white/50">
    <div class="mx-auto max-w-5xl px-4 py-3 flex items-center justify-between gap-4">
        <div class="flex items-center gap-2 font-bold tracking-tight text-slate-900">
            <span class="size-7 rounded-md bg-gradient-to-br from-blue-600 to-amber-500 shadow-md shadow-blue-500/30 inline-block"></span>
            <a class="hover:opacity-90" href="{{ route('shop.products.index') }}">Molitor Shop</a>
        </div>
        <div class="text-sm">
            <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.products.index') }}">Termékek</a>
        </div>
    </div>
</nav>
<div class="mx-auto max-w-5xl px-4">
    <div class="py-8">
        <h1 class="m-0 text-2xl font-semibold">@yield('page_title', 'Webshop')</h1>
        @hasSection('page_subtitle')
            <p class="mt-1 text-slate-500">@yield('page_subtitle')</p>
        @endif
    </div>
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="p-4 sm:p-5 lg:p-6">
            @yield('content')
        </div>
    </div>
    <div class="text-center text-slate-500 text-sm py-8">© {{ date('Y') }} Molitor Shop</div>
</div>
@stack('scripts')
</body>
</html>
