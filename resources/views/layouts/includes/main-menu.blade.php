<div class="text-sm flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
    <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.products.index') }}">Termékek</a>
    <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.cart.index') }}">Kosár @isset($cartCount)<span class="ml-1 inline-flex items-center justify-center min-w-5 h-5 px-1 text-xs rounded-full bg-slate-900 text-white">{{ $cartCount }}</span>@endisset</a>
    @auth
        <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.orders.index') }}">Megrendeléseim</a>
        <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.profile.show') }}">Profil</a>
        <form method="POST" action="{{ route('shop.logout') }}" class="inline-flex items-center gap-2">
            @csrf
            <span class="text-slate-700">{{ auth()->user()->name }}</span>
            <button type="submit" class="text-slate-700 hover:text-slate-900">Kijelentkezés</button>
        </form>
    @else
        <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.login') }}">Belépés</a>
        <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.register') }}">Regisztráció</a>
    @endauth
</div>
