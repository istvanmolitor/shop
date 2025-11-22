@extends('shop::layouts.shop')

@section('title', $category->name . ' – Molitor Shop')
@section('page_title', $category->name)
@section('page_subtitle')Tekintse meg a(z) {{ $category->name }} kategóriában elérhető termékeket @endsection

@section('content')
    @if($category->description)
        <div class="mb-8 prose prose-slate max-w-none">
            {{ $category->description }}
        </div>
    @endif
    @php($children = $category->productCategories ?? collect())
    @if($children->isNotEmpty())
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Alkategóriák</h3>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($children as $child)
                    <a href="{{ route('shop.categories.show', $child) }}" class="group block rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="aspect-[4/3] w-full overflow-hidden rounded-t-xl bg-slate-100 flex items-center justify-center">
                            @php($img = $child->image_url ?: ($child->image ? asset('storage/' . ltrim($child->image, '/')) : null))
                            @if($img)
                                <img src="{{ $img }}" alt="{{ $child->name }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <div class="text-3xl font-semibold text-slate-500">
                                    {{ mb_substr($child->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="p-4 text-center">
                            <div class="text-slate-900 font-medium truncate">{{ $child->name }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    @livewire('molitor.shop.http.livewire.products-list-component', ['categoryId' => $category->id])
@endsection
