<div>
    @if(isset($shopCategories) && $shopCategories->isNotEmpty())
        <ul class="space-y-1">
            @foreach($shopCategories as $category)
                @include('shop::livewire.partials.sidebar-category-node', [
                    'category' => $category,
                    'level' => 0,
                ])
            @endforeach
        </ul>
    @else
        <div class="text-slate-500 text-sm">Nincsenek kategóriák.</div>
    @endif
</div>
