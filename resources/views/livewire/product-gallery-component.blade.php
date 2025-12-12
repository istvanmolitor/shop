<div>
    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
        <!-- Main image -->
        <div class="relative pt-[70%] bg-slate-100">
            @php
                $hasImages = count($images) > 0;
                $current = $hasImages ? ($images[$currentIndex] ?? null) : null;
                $currentUrl = $current['url'] ?? null;
            @endphp
            @if($currentUrl)
                <img
                    class="absolute inset-0 w-full h-full object-cover cursor-zoom-in"
                    src="{{ $currentUrl }}"
                    alt="{{ $current['alt'] ?? '' }}"
                    wire:click="openLightbox"
                >
            @endif
        </div>

        <!-- Thumbnails -->
        @if($hasImages && count($images) > 1)
            <div class="p-3 border-t border-slate-200">
                <div class="flex gap-2 overflow-x-auto">
                    @foreach($images as $idx => $img)
                        @php $active = $idx === $currentIndex; @endphp
                        <button
                            type="button"
                            class="relative shrink-0 w-16 h-16 rounded-md overflow-hidden border {{ $active ? 'border-blue-600 ring-2 ring-blue-200' : 'border-slate-200 hover:border-slate-300' }}"
                            wire:click="select({{ $idx }})"
                            aria-label="Kép {{ $idx + 1 }} kiválasztása"
                        >
                            @if($img['url'])
                                <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}" class="absolute inset-0 w-full h-full object-cover">
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Lightbox overlay -->
    @if($showLightbox)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/70" wire:click="closeLightbox"></div>

            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="relative max-w-5xl w-full">
                    <button type="button" class="absolute -top-10 right-0 text-white hover:text-slate-200" wire:click="closeLightbox" aria-label="Bezárás">
                        ✕
                    </button>

                    <div class="relative pt-[60%] bg-black/20 rounded-lg overflow-hidden">
                        @php $url = $images[$currentIndex]['url'] ?? null; @endphp
                        @if($url)
                            <img src="{{ $url }}" alt="" class="absolute inset-0 w-full h-full object-contain bg-black">
                        @endif

                        @if(count($images) > 1)
                            <button type="button" class="absolute inset-y-0 left-0 w-12 flex items-center justify-center text-white/70 hover:text-white" wire:click="prev" aria-label="Előző">‹</button>
                            <button type="button" class="absolute inset-y-0 right-0 w-12 flex items-center justify-center text-white/70 hover:text-white" wire:click="next" aria-label="Következő">›</button>
                        @endif
                    </div>

                    @if(count($images) > 1)
                        <div class="mt-3 flex items-center justify-center gap-2">
                            @foreach($images as $i => $img)
                                <button type="button" class="w-2 h-2 rounded-full {{ $i === $currentIndex ? 'bg-white' : 'bg-white/40 hover:bg-white/70' }}" wire:click="select({{ $i }})" aria-label="Ugrás a {{ $i + 1 }}. képre"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
