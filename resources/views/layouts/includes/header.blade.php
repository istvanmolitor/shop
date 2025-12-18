<header>
    <nav class="sticky top-0 z-10 border-b border-slate-200 bg-white/70 backdrop-blur supports-[backdrop-filter]:bg-white/50">
        <div class="w-full px-4 py-3 max-w-7xl mx-auto flex items-center justify-between gap-4 relative">
            @include('shop::layouts.includes.logo')

            <div class="hidden md:block flex-1">
                @include('shop::layouts.includes.search')
            </div>

            <div class="hidden md:block">
                @include('shop::layouts.includes.main-menu')
            </div>

            <div class="hidden md:block ml-2">
                @include('language::components.language-switcher')
            </div>

            <div class="relative ml-2">
                @livewire('shop.header-cart')
            </div>

            <button id="mobile-menu-button" type="button" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400" aria-controls="mobile-menu" aria-expanded="false">
                <span class="sr-only">{{ __('shop::common.menu.mobile_open') }}</span>
                <x-filament::icon icon="heroicon-o-bars-3" class="w-6 h-6" />
            </button>
        </div>

        <div id="mobile-menu" class="md:hidden hidden border-t border-slate-200">
            <div class="px-4 py-3 space-y-3">
                @include('shop::layouts.includes.search')
                @include('shop::layouts.includes.main-menu')
                @include('language::components.language-switcher')
            </div>
        </div>
    </nav>

    <script>
        (function(){
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            if(!btn || !menu) return;
            function closeMenu(){
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded','false');
            }
            function openMenu(){
                menu.classList.remove('hidden');
                btn.setAttribute('aria-expanded','true');
            }
            btn.addEventListener('click', function(){
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                if(expanded){ closeMenu(); } else { openMenu(); }
            });
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape'){ closeMenu(); } });
            document.addEventListener('click', function(e){
                if(!menu.contains(e.target) && !btn.contains(e.target)){
                    closeMenu();
                }
            });
            // Close on resize to md+
            window.addEventListener('resize', function(){ if(window.innerWidth >= 768){ closeMenu(); } });
        })();
    </script>
</header>
