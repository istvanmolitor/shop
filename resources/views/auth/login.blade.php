@extends('shop::layouts.shop')

@section('title', 'Bejelentkezés – Molitor Shop')
@section('page_title', 'Bejelentkezés')
@section('page_subtitle', 'Lépjen be a vásárláshoz')

@section('content')
    <form method="POST" action="{{ route('shop.login.post') }}" class="max-w-md space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail cím</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Jelszó</label>
            <input id="password" name="password" type="password" required class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex items-center gap-2">
            <input id="remember" name="remember" type="checkbox" class="rounded border-slate-300">
            <label for="remember" class="text-sm text-slate-700">Emlékezzen rám</label>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 border border-slate-900 bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-slate-800">Belépés</button>
            <a href="{{ route('shop.register') }}" class="text-sm text-slate-700 hover:text-slate-900">Nincs még fiókja? Regisztráció</a>
        </div>
    </form>
@endsection
