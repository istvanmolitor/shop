<div class="text-sm flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
    <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.products.index') }}">{{ __('shop::common.menu.products') }}</a>

    @auth
        <div class="relative" x-data="{ open: false }" x-on:keydown.escape.window="open = false">
            <button type="button"
                    x-on:click="open = !open"
                    class="inline-flex items-center gap-2 text-slate-700 hover:text-slate-900 focus:outline-none"
                    aria-haspopup="menu"
                    x-bind:aria-expanded="open">
                <svg class="h-6 w-6 text-slate-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0v.75a.75.75 0 01-.75.75h-13.5a.75.75 0 01-.75-.75v-.75z" />
                </svg>
                <span class="hidden md:inline text-slate-700">{{ auth()->user()->name }}</span>
            </button>

            <div x-cloak x-show="open" x-transition
                 x-on:click.outside="open = false"
                 class="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none z-20">
                <div class="py-1" role="menu" aria-orientation="vertical">
                    <a href="{{ route('shop.orders.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50" role="menuitem">{{ __('shop::common.menu.orders') }}</a>
                    <a href="{{ route('shop.profile.show') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50" role="menuitem">{{ __('shop::common.menu.profile') }}</a>
                    @can('acl', 'admin')
                        <a href="/admin" class="block px-4 py-2 text-slate-700 hover:bg-slate-50" role="menuitem">{{ __('shop::common.menu.admin') }}</a>
                    @endcan
                    <form method="POST" action="{{ route('shop.logout') }}" class="block" role="none">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-slate-700 hover:bg-slate-50" role="menuitem">{{ __('shop::common.menu.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.login') }}">{{ __('shop::common.menu.login') }}</a>
        <a class="text-slate-700 hover:text-slate-900" href="{{ route('shop.register') }}">{{ __('shop::common.menu.register') }}</a>
    @endauth
</div>
