<div>
    @if($items->isEmpty())
        <p class="text-slate-500">A kosara üres.</p>
        <p class="mt-3"><a class="inline-flex items-center gap-2 border border-blue-600 bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 no-underline" href="{{ route('shop.products.index') }}">Vissza a termékekhez</a></p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left p-3 border-b border-slate-200">Termék</th>
                        <th class="text-right p-3 border-b border-slate-200">Egységár</th>
                        <th class="text-right p-3 border-b border-slate-200">Mennyiség</th>
                        <th class="text-right p-3 border-b border-slate-200">Részösszeg</th>
                        <th class="text-right p-3 border-b border-slate-200">Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Összesített végösszeg a kosárhoz (alapértelmezett devizanemben)
                        /** @var \Molitor\Currency\Services\Price $grandTotal */
                        $grandTotal = new \Molitor\Currency\Services\Price(0, null);
                    @endphp
                    @foreach($items as $item)
                        @php
                            $product = $item->product;
                            // Egységár a termék beállított devizanemében, megjelenítéshez átváltva az alapértelmezettre
                            /** @var \Molitor\Currency\Services\Price $unitPrice */
                            $unitPrice = $product->getPrice();
                            $unitPriceDefault = $unitPrice->exchangeDefault();

                            // Részösszeg: egységár * mennyiség, alapértelmezett devizanemben
                            $lineSubtotal = $unitPrice->multiple((int)$item->quantity)->exchangeDefault();

                            // Végösszeg növelése a részösszeggel (árfolyam egyeztetés automatikusan megtörténik)
                            $grandTotal = $grandTotal->addition($lineSubtotal);

                            $img = optional($product->productImages->first());
                            $imgUrl = $img?->getSrc();

                            // Use product_id as key for session-based carts, id for database carts
                            $itemKey = $item->id ?? 'p_' . $item->product_id;
                        @endphp
                        <tr class="border-t border-slate-200">
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @php($fallback = asset('vendor/shop/product/noimage.png'))
                                    @php($src = $imgUrl ?: $fallback)
                                    <img class="w-12 h-12 object-cover rounded-md border border-slate-200" src="{{ $src }}" alt="{{ $product->name }}">
                                    <div>
                                        <a class="font-medium text-slate-900 hover:text-slate-950 no-underline" href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
                                        <div class="text-slate-500 text-xs">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">{{ $unitPriceDefault }}</td>
                            <td class="p-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="decrementQty('{{ $itemKey }}')" class="px-2 py-1 rounded-md border border-slate-300 hover:bg-slate-50" aria-label="Csökkentés">−</button>
                                    <input type="number" min="0" wire:model.defer="qty.{{ $itemKey }}" class="w-20 border border-slate-300 rounded-md px-2 py-1 text-right" />
                                    <button wire:click="incrementQty('{{ $itemKey }}')" class="px-2 py-1 rounded-md border border-slate-300 hover:bg-slate-50" aria-label="Növelés">+</button>
                                    <button wire:click="saveQty('{{ $itemKey }}')" class="px-2 py-1 rounded-md border border-blue-600 text-white bg-blue-600 hover:bg-blue-700">Mentés</button>
                                </div>
                            </td>
                            <td class="p-3 text-right whitespace-nowrap font-medium">{{ $lineSubtotal }}</td>
                            <td class="p-3 text-right">
                                <button wire:click="removeItem('{{ $itemKey }}')" class="px-3 py-1.5 rounded-md border border-rose-600 text-white bg-rose-600 hover:bg-rose-700">Törlés</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 border-t border-slate-200">
                        <td class="p-3" colspan="3"><span class="font-semibold">Végösszeg</span></td>
                        <td class="p-3 text-right font-bold" colspan="2">{{ $grandTotal->exchangeDefault() }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
