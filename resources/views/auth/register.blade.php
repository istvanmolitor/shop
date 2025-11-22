@extends('shop::layouts.shop')

@section('title', 'Regisztráció – Molitor Shop')
@section('page_title', 'Regisztráció')
@section('page_subtitle', 'Hozzon létre egy új vásárlói fiókot')

@section('content')
    <form method="POST" action="{{ route('shop.register.post') }}" class="max-w-md space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Név</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail cím</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Jelszó</label>
            <input id="password" name="password" type="password" required class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Jelszó megerősítése</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full border border-slate-300 rounded-md px-3 py-2">
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Regisztráció</button>
            <a href="{{ route('shop.login') }}" class="text-sm text-slate-700 hover:text-slate-900">Már van fiókja? Bejelentkezés</a>
        </div>
    </form>
@endsection
