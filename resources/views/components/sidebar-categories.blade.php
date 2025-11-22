
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="p-4 sm:p-5 lg:p-6">
            <h2 class="text-base font-semibold text-slate-900 mb-3">Kategóriák</h2>
            @if(isset($shopCategories) && $shopCategories->isNotEmpty())
                <ul class="space-y-1">
                    @foreach($shopCategories as $category)
                        @include('shop::components.partials.category-tree', ['category' => $category])
                    @endforeach
                </ul>
            @else
                <div class="text-slate-500 text-sm">Nincsenek kategóriák.</div>
            @endif
        </div>
    </div>
