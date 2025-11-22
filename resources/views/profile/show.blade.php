@extends('shop::layouts.shop')

@section('title', 'Profil – Molitor Shop')
@section('page_title', 'Saját profil')
@section('page_subtitle', 'Adatainak kezelése')

@section('content')
    @if(session('status'))
        <div class="mb-4 p-3 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('status') }}</div>
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <h2 class="mt-0 text-lg font-semibold">Felhasználói adatok</h2>
            <form method="POST" action="{{ route('shop.profile.update') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Név</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail cím</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center gap-2 border border-slate-900 bg-slate-900 text-white px-4 py-2 rounded-md hover:bg-slate-800">Mentés</button>
                </div>
            </form>
        </div>
        <div>
            <h2 class="mt-0 text-lg font-semibold">Vásárlói profil</h2>
            @if($customer)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="text-slate-500">Azonosító</div>
                        <div>{{ $customer->id }}</div>
                        <div class="text-slate-500">Név</div>
                        <div>{{ $customer->name }}</div>
                    </div>
                    <p class="mt-3 text-slate-500">Számlázási és szállítási címek kezelése a későbbiekben lesz elérhető.</p>
                </div>
            @else
                <p class="text-slate-500">Ehhez a felhasználóhoz még nem tartozik vásárlói profil.</p>
            @endif
        </div>
    </div>
@endsection
