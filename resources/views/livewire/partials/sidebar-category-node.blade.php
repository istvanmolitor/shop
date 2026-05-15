@php($children = $category->productCategories)
@php($hasChildren = $children && $children->isNotEmpty())
@php($isOpen = $expanded[$category->id] ?? false)

<li class="flex flex-col" wire:key="sidebar-cat-{{ $category->id }}">
    <div class="flex items-center">
        @if($hasChildren)
            <button type="button"
                    wire:click.prevent="toggleExpand({{ (int)$category->id }})"
                    class="mr-1 inline-flex h-6 w-6 items-center justify-center rounded hover:bg-slate-100 text-slate-600"
                    aria-label="{{ $isOpen ? __('shop::common.categories.collapse') : __('shop::common.categories.expand') }}">
                @if($isOpen)
                    <x-theme:icon name="minus" class="h-4 w-4" />
                @else
                    <x-theme:icon name="plus" class="h-4 w-4" />
                @endif
            </button>
        @else
            <span class="mr-1 inline-block h-6 w-6"></span>
        @endif

        <a href="{{ route('shop.categories.show', $category) }}"
           class="flex-1 block px-2 py-1.5 rounded-md text-slate-700 hover:text-slate-900 hover:bg-slate-100">
            {{ $category->name }}
        </a>
    </div>

    @if($hasChildren && $isOpen)
        <ul class="ml-7 mt-1 space-y-1">
            @foreach($children as $child)
                @include('shop::livewire.partials.sidebar-category-node', [
                    'category' => $child,
                    'level' => ($level ?? 0) + 1,
                ])
            @endforeach
        </ul>
    @endif
</li>
