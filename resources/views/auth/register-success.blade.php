@extends('shop::layouts.app')

@section('title', 'Sikeres regisztráció – Molitor Shop')
@section('page_title', 'Sikeres regisztráció')
@section('page_subtitle', 'Kérjük, erősítse meg e-mail címét')

@section('content')
    <div class="max-w-xl space-y-4">
        <p class="text-slate-800">
            Sikeresen regisztrált! Küldtünk egy megerősítő e-mailt a megadott címre. Kérjük, nyissa meg az e-mailt,
            és kattintson a megerősítő linkre az e-mail cím hitelesítéséhez.
        </p>

        <p class="text-slate-700">
            A hitelesítés után jelentkezzen be az alábbi gombbal.
        </p>

        <div>
            <a href="{{ route('shop.login') }}"
               class="inline-flex items-center gap-2 border border-slate-900 bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-slate-800">
                Bejelentkezés
            </a>
        </div>

        @if (session('status'))
            <div class="text-green-700">{{ session('status') }}</div>
        @endif
    </div>
@endsection
