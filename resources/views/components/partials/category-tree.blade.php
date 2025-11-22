<li>
    <span class="block px-2 py-1.5 rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">{{ $category->name }}</span>

    @php($children = $category->productCategories)
    @if($children && $children->isNotEmpty())
        <ul class="ml-4 mt-1 space-y-1">
            @foreach($children as $child)
                @include('shop::components.partials.category-tree', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
