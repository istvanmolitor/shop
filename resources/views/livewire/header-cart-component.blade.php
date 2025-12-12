@php
    $uid = 'header-cart-' . uniqid();
@endphp
<div class="relative" id="{{ $uid }}">
    <button id="{{ $uid }}-button" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400" aria-controls="{{ $uid }}-panel" aria-expanded="false">
        <span class="sr-only">{{ __('shop::common.header_cart.open') }}</span>
        <span class="relative inline-block">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25h9.75a2.25 2.25 0 002.2-1.772l1.163-5.813A1.125 1.125 0 0019.512 4.5H5.25m2.25 9.75L5.106 5.272M7.5 14.25L4.875 4.5m0 0H3.375M9 20.25a.75.75 0 100-1.5.75.75 0 000 1.5zm9 0a.75.75 0 100-1.5.75.75 0 000 1.5z" />
            </svg>
            @if($this->count > 0)
                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center rounded-full bg-rose-600 text-white text-xs px-1.5 min-w-[1.25rem] h-5 leading-none">{{ $this->count }}</span>
            @endif
        </span>
    </button>

    <div id="{{ $uid }}-panel" class="hidden absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-md shadow-lg z-20">
        <div class="p-3">
            @if($this->count === 0)
                <p class="text-slate-500">{{ __('shop::common.header_cart.empty') }}</p>
            @else
                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                    @foreach($this->items as $item)
                        @php
                            $product = $item->product;
                            $price = (float)($product->price ?? 0);
                            $subtotal = $price * (int)$item->quantity;
                            $img = optional($product->productImages->first());
                            $imgUrl = $img?->getSrc();
                        @endphp
                        <div class="py-2 flex items-center gap-3">
                            @php($fallback = asset('vendor/shop/product/noimage.png'))
                            @php($src = $imgUrl ?: $fallback)
                            <img class="w-10 h-10 object-cover rounded border border-slate-200" src="{{ $src }}" alt="{{ $product->name }}">
                            <div class="min-w-0 flex-1">
                                <a class="block text-sm font-medium text-slate-900 truncate no-underline hover:text-slate-950" href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
                                <div class="text-xs text-slate-500">{{ (int)$item->quantity }} × {{ $product->getPrice() }}</div>
                            </div>
                            <div class="text-sm font-medium whitespace-nowrap">{{ $product->getPrice()->multiple($item->quantity) }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm text-slate-600">{{ __('shop::common.header_cart.total') }}</span>
                    <span class="text-sm font-semibold">{{ $this->total }}</span>
                </div>
            @endif
            <div class="mt-3">
                <a href="{{ route('shop.cart.index') }}" class="w-full inline-flex items-center justify-center gap-2 border border-slate-300 px-3 py-2 rounded-md hover:bg-slate-50 no-underline">{{ __('shop::common.header_cart.view_cart') }}</a>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const btn = document.getElementById('{{ $uid }}-button');
            const panel = document.getElementById('{{ $uid }}-panel');
            if(!btn || !panel) return;
            function close(){ panel.classList.add('hidden'); btn.setAttribute('aria-expanded','false'); }
            function open(){ panel.classList.remove('hidden'); btn.setAttribute('aria-expanded','true'); }
            btn.addEventListener('click', function(e){
                e.stopPropagation();
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                if(expanded){ close(); } else { open(); }
            });
            document.addEventListener('click', function(e){
                if(!panel.contains(e.target) && !btn.contains(e.target)){
                    close();
                }
            });
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape'){ close(); } });
            window.addEventListener('resize', function(){ if(window.innerWidth < 768){ close(); } });
        })();
    </script>
</div>
