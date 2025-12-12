<div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4">
    <h3 class="mt-0 mb-3 text-base font-semibold">Szűrés</h3>

    <div class="space-y-4">
        <div>
            <div class="text-sm font-medium text-slate-700 mb-1">Ár</div>
            <div class="flex items-center gap-2">
                <input type="number" step="0.01" placeholder="Min" wire:model.debounce.500ms="minPrice" class="w-24 border border-slate-300 rounded-md px-2 py-1 text-sm">
                <span class="text-slate-400">–</span>
                <input type="number" step="0.01" placeholder="Max" wire:model.debounce.500ms="maxPrice" class="w-24 border border-slate-300 rounded-md px-2 py-1 text-sm">
            </div>
        </div>

        @foreach($fields as $field)
            @php $current = $selectedOptions[$field->id] ?? []; @endphp
            <div>
                <div class="text-sm font-medium text-slate-700 mb-1">{{ $field->name }}</div>
                <div class="space-y-1">
                    @foreach($field->productFieldOptions as $option)
                        <label class="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                wire:click="toggleOption({{ $field->id }}, {{ $option->id }})"
                                @checked(in_array($option->id, $current))
                            >
                            <span>{{ $option->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
