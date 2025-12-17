<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Livewire\Component;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
use Molitor\Order\Models\OrderShipping;
use Molitor\Order\Services\ShippingHandler;
use Illuminate\Support\Collection;

class ShippingMethodComponent extends Component
{
    public ?int $selectedShippingId = null;
    public array $shippingData = [];

    protected OrderShippingRepositoryInterface $shippingRepository;
    protected ShippingHandler $shippingHandler;

    protected $listeners = [
        'shippingDataUpdated' => 'handleShippingDataUpdate'
    ];

    protected $rules = [
        'selectedShippingId' => 'required|integer|exists:order_shippings,id',
        'shippingData' => 'array',
    ];

    protected $messages = [
        'selectedShippingId.required' => 'Kérjük, válasszon szállítási módot.',
        'selectedShippingId.exists' => 'A kiválasztott szállítási mód nem érvényes.',
    ];

    public function boot(OrderShippingRepositoryInterface $shippingRepository, ShippingHandler $shippingHandler): void
    {
        $this->shippingRepository = $shippingRepository;
        $this->shippingHandler = $shippingHandler;
    }

    public function mount(): void
    {
        $checkout = session('checkout', []);
        $this->selectedShippingId = isset($checkout['order_shipping_id'])
            ? (int)$checkout['order_shipping_id']
            : null;
        $this->shippingData = $checkout['shipping_data'] ?? [];
    }

    public function getShippingMethodsProperty(): Collection
    {
        return $this->shippingRepository->getAll();
    }

    public function updatedSelectedShippingId($value): void
    {
        // Cast to int to ensure type safety
        $this->selectedShippingId = $value ? (int)$value : null;

        // Reset shipping data when changing shipping method
        $this->shippingData = [];
    }

    public function handleShippingDataUpdate($data): void
    {
        $this->shippingData = $data;
    }

    public function getSelectedShippingTypeComponentProperty(): ?string
    {
        if (!$this->selectedShippingId) {
            return null;
        }

        $selectedMethod = $this->shippingMethods->firstWhere('id', $this->selectedShippingId);

        if (!$selectedMethod || !$selectedMethod->type) {
            return null;
        }

        return $this->shippingHandler->getLivewireComponentName($selectedMethod->type);
    }

    public function submit(): mixed
    {
        $this->validate();

        // Validate shipping data based on selected shipping type
        if ($this->selectedShippingId) {
            $selectedMethod = $this->shippingMethods->firstWhere('id', $this->selectedShippingId);

            if ($selectedMethod && $selectedMethod->type) {
                $shippingType = $this->shippingHandler->getShippingType($selectedMethod->type);

                if ($shippingType) {
                    try {
                        // Validate shipping data using the shipping type's validation rules
                        $this->shippingData = $shippingType->validate($this->shippingData);
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        // Map validation errors to shippingData.* format for proper display
                        $errors = [];
                        foreach ($e->errors() as $key => $messages) {
                            $errors['shippingData.' . $key] = $messages;
                        }

                        throw \Illuminate\Validation\ValidationException::withMessages($errors);
                    }
                }
            }
        }

        $checkout = session('checkout', []);
        $checkout['order_shipping_id'] = $this->selectedShippingId;
        $checkout['shipping_data'] = $this->shippingData;

        session(['checkout' => $checkout]);

        return redirect()->route('shop.checkout.payment');
    }

    public function render()
    {
        return view('shop::livewire.shipping-method-component');
    }
}

