<div>
    <form wire:submit.prevent="submit" class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mx-auto w-full max-w-2xl md:col-span-2">
                <h3 class="font-semibold mb-2">{{ __('shop::common.checkout.shipping.method') }}</h3>
                <div class="p-4 border rounded space-y-3 @error('selectedShippingId') border-red-300 @enderror">
                    @foreach($this->shippingMethods as $method)
                        @php
                            $price = $method->getPrice()->exchangeDefault();
                        @endphp
                        <label class="flex items-start gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="radio" wire:model.live="selectedShippingId" value="{{ $method->id }}"
                                   class="mt-1">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">{{ $method->name }}</span>
                                    <span class="font-semibold text-gray-900">{{ $price }}</span>
                                </div>
                                @if($method->description)
                                    <div class="text-sm text-gray-600 mt-1">{!! $method->description !!}</div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('selectedShippingId')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dynamic Shipping Type Forms --}}
            <div class="md:col-span-2">
                @if($selectedShippingId && $this->selectedShippingTypeComponent)
                    @php
                        $selectedMethod = $this->shippingMethods->firstWhere('id', $selectedShippingId);
                        $shippingType = $selectedMethod && $selectedMethod->type
                            ? $this->shippingHandler->getShippingType($selectedMethod->type)
                            : null;
                    @endphp
                    @if($shippingType)
                        <div class="p-4 border rounded space-y-3">
                            <h3 class="font-semibold mb-2">{{ $shippingType->getLabel() }}</h3>
                            <div class="shipping-type-content">
                                <livewire:dynamic-component
                                    :is="$this->selectedShippingTypeComponent"
                                    :shippingData="$shippingData"
                                    :key="'shipping-' . $selectedShippingId"
                                />
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('shop.cart.index') }}" class="text-gray-600">
                {{ __('shop::common.checkout.shipping.back_to_cart') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded">
                {{ __('shop::common.checkout.shipping.continue_to_payment') }}
            </button>
        </div>
    </form>
</div>

