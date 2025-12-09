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
                    @foreach($items as $item)
                        @php
                            $product = $item->product;
                            $price = (float)($product->price ?? 0);
                            $subtotal = $price * (int)$item->quantity;
                            $img = optional($product->productImages->first());
                            $imgUrl = $img?->getSrc();
                        @endphp
                        <tr class="border-t border-slate-200">
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @if($imgUrl)
                                        <img class="w-12 h-12 object-cover rounded-md border border-slate-200" src="{{ $imgUrl }}" alt="{{ $product->name }}">
                                    @endif
                                    <div>
                                        <a class="font-medium text-slate-900 hover:text-slate-950 no-underline" href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
                                        <div class="text-slate-500 text-xs">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-right whitespace-nowrap">{{ $product->getPrice() }}</td>
                            <td class="p-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="decrementQty({{ $item->id }})" class="px-2 py-1 rounded-md border border-slate-300 hover:bg-slate-50" aria-label="Csökkentés">−</button>
                                    <input type="number" min="0" wire:model.defer="qty.{{ $item->id }}" class="w-20 border border-slate-300 rounded-md px-2 py-1 text-right" />
                                    <button wire:click="incrementQty({{ $item->id }})" class="px-2 py-1 rounded-md border border-slate-300 hover:bg-slate-50" aria-label="Növelés">+</button>
                                    <button wire:click="saveQty({{ $item->id }})" class="px-2 py-1 rounded-md border border-blue-600 text-white bg-blue-600 hover:bg-blue-700">Mentés</button>
                                </div>
                            </td>
                            <td class="p-3 text-right whitespace-nowrap font-medium">eeeeee</td>
                            <td class="p-3 text-right">
                                <button wire:click="removeItem({{ $item->id }})" class="px-3 py-1.5 rounded-md border border-rose-600 text-white bg-rose-600 hover:bg-rose-700">Törlés</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 border-t border-slate-200">
                        <td class="p-3" colspan="3"><span class="font-semibold">Végösszeg</span></td>
                        <td class="p-3 text-right font-bold" colspan="2">pppppp</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-4 flex items-center gap-3">
            <a class="inline-flex items-center gap-2 border border-slate-300 px-3 py-2 rounded-md hover:bg-slate-50 no-underline" href="{{ route('shop.products.index') }}">Vissza a termékekhez</a>
            @auth
                <a class="inline-flex items-center gap-2 border border-emerald-600 bg-emerald-600 text-white px-3 py-2 rounded-md hover:bg-emerald-700 no-underline" href="{{ route('shop.checkout.show') }}">Tovább a megrendeléshez</a>
            @else
                <a class="inline-flex items-center gap-2 border border-emerald-600 text-emerald-700 px-3 py-2 rounded-md hover:bg-emerald-50 no-underline" href="{{ route('shop.login') }}">Bejelentkezés a megrendeléshez</a>
            @endauth
        </div>
    @endif
</div>
