<div class="flex-1 max-w-xl">
    <form method="get" action="{{ route('shop.products.index') }}" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Keresés terméknév, cikkszám..."
               class="w-full rounded-md border border-slate-300 px-3 py-2" />
        <button type="submit" class="rounded-md bg-slate-900 text-white px-3 py-2">Keresés</button>
    </form>
</div>
