@php
    $mainMenu = app(\Molitor\Menu\Services\MenuManager::class)->mainMenu();
@endphp

<div class="text-sm flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
    @foreach($mainMenu->getMenuItems() as $menuItem)
        @if($menuItem->count() > 0)
            {{-- Menu item with submenus - show as dropdown --}}
            <div class="relative" x-data="{ open: false }" x-on:keydown.escape.window="open = false">
                <button type="button"
                        x-on:click="open = !open"
                        class="inline-flex items-center gap-1 text-slate-700 hover:text-slate-900 focus:outline-none {{ $menuItem->isActive() ? 'font-semibold' : '' }}"
                        aria-haspopup="menu"
                        x-bind:aria-expanded="open">
                    @if($menuItem->getIcon())
                        <x-filament::icon :icon="$menuItem->getIcon()" class="w-5 h-5" />
                    @endif
                    {{ $menuItem->getLabel() }}
                    <x-filament::icon icon="heroicon-m-chevron-down" class="w-4 h-4" />
                </button>

                <div x-cloak x-show="open" x-transition
                     x-on:click.outside="open = false"
                     class="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none z-20">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        @foreach($menuItem->getMenuItems() as $subMenuItem)
                            @if($subMenuItem->getName() === 'logout')
                                <form method="POST" action="{{ $subMenuItem->getUrl() }}" class="block" role="none">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-slate-700 hover:bg-slate-50 inline-flex items-center gap-2" role="menuitem">
                                        @if($subMenuItem->getIcon())
                                            <x-filament::icon :icon="$subMenuItem->getIcon()" class="w-5 h-5" />
                                        @endif
                                        {{ $subMenuItem->getLabel() }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ $subMenuItem->getUrl() }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50 {{ $subMenuItem->isActive() ? 'font-semibold' : '' }}" role="menuitem">
                                    @if($subMenuItem->getIcon())
                                        <x-filament::icon :icon="$subMenuItem->getIcon()" class="w-5 h-5" />
                                    @endif
                                    {{ $subMenuItem->getLabel() }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @elseif($menuItem->getName() === 'logout')
            {{-- Logout item needs a form --}}
            <form method="POST" action="{{ $menuItem->getUrl() }}" class="inline-block">
                @csrf
                <button type="submit" class="text-slate-700 hover:text-slate-900 inline-flex items-center gap-1">
                    @if($menuItem->getIcon())
                        <x-filament::icon :icon="$menuItem->getIcon()" class="w-5 h-5" />
                    @endif
                    {{ $menuItem->getLabel() }}
                </button>
            </form>
        @else
            {{-- Regular menu item without submenus --}}
            <a class="text-slate-700 hover:text-slate-900 {{ $menuItem->isActive() ? 'font-semibold' : '' }} inline-flex items-center gap-1"
               href="{{ $menuItem->getUrl() }}">
                @if($menuItem->getIcon())
                    <x-filament::icon :icon="$menuItem->getIcon()" class="w-5 h-5" />
                @endif
                {{ $menuItem->getLabel() }}
            </a>
        @endif
    @endforeach
</div>
