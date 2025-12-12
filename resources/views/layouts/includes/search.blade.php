@php
    $searchId = uniqid('search-');
    $toggleId = $searchId.'-toggle';
    $panelId = $searchId.'-panel';
    $isOpen = filled(request('q'));
@endphp

<form method="get" action="{{ route('shop.products.index') }}" class="flex items-center justify-end gap-2 w-full" id="{{ $searchId }}">
    <div id="{{ $panelId }}" class="flex-1 items-center gap-2 {{ $isOpen ? 'flex' : 'hidden' }}">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('shop::common.search.placeholder') }}"
               class="w-full rounded-md border border-slate-300 px-3 py-2" />
        <button type="submit" class="rounded-md bg-slate-900 text-white px-3 py-2">{{ __('shop::common.search.submit') }}</button>
    </div>

    <button type="button" id="{{ $toggleId }}" class="inline-flex items-center justify-center rounded-md p-2 text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400" aria-controls="{{ $panelId }}" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
        <span class="sr-only">{{ __('shop::common.search.open') }}</span>
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
        </svg>
    </button>
</form>

<script>
    (function(){
        const root = document.getElementById(@json($searchId));
        if(!root) return;
        const btn = document.getElementById(@json($toggleId));
        const panel = document.getElementById(@json($panelId));
        if(!btn || !panel) return;
        function closePanel(){ panel.classList.remove('flex'); panel.classList.add('hidden'); btn.setAttribute('aria-expanded','false'); }
        function openPanel(){ panel.classList.remove('hidden'); panel.classList.add('flex'); btn.setAttribute('aria-expanded','true'); const input = panel.querySelector('input[name="q"]'); if(input){ setTimeout(()=>input.focus(), 0); } }
        btn.addEventListener('click', function(){
            const isHidden = panel.classList.contains('hidden');
            if(isHidden){ openPanel(); } else { closePanel(); }
        });
        document.addEventListener('click', function(e){
            if(!root.contains(e.target) && btn.getAttribute('aria-expanded') === 'true'){
                closePanel();
            }
        });
        document.addEventListener('keydown', function(e){ if(e.key === 'Escape'){ closePanel(); } });
    })();
</script>
