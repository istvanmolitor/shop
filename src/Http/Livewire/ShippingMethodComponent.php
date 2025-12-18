<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
use Molitor\Order\Services\ShippingHandler;
use Illuminate\Support\Collection;
use Molitor\Order\Services\ShippingType;

class ShippingMethodComponent extends Component
{
    public ?int $selectedShippingId = null;
    public array $shippingData = [];
    public array $shippingErrors = [];

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

    public function boot(
        OrderShippingRepositoryInterface $shippingRepository,
        ShippingHandler $shippingHandler
    ): void
    {
        $this->shippingRepository = $shippingRepository;
        $this->shippingHandler = $shippingHandler;
    }

    public function getShippingType(): ShippingType|null
    {
        if (!$this->selectedShippingId) {
            return null;
        }
        $selectedMethod = $this->shippingMethods->firstWhere('id', $this->selectedShippingId);
        if (!$selectedMethod || !$selectedMethod->type) {
            return null;
        }
        return $this->shippingHandler->getShippingType($selectedMethod->type);
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
        $this->selectedShippingId = (int)$value ?? null;
        $this->shippingData = [];
        $this->shippingErrors = [];
    }

    public function handleShippingDataUpdate($data): void
    {
        $this->shippingData = $data;
    }

    public function getSelectedShippingTypeComponentProperty(): string|null
    {
        $shippingType = $this->getShippingType();
        return $shippingType->getLivewireComponent();
    }

    public function submit(): mixed
    {
        $this->validate();

        $this->shippingErrors = [];

        $shippinType = $this->getShippingType();
        if ($shippinType) {
            try {
                $this->shippingData = $shippinType->validate($this->shippingData);
            } catch (ValidationException $e) {
                $this->shippingErrors = $e->errors();

                $errors = [];
                foreach ($e->errors() as $key => $messages) {
                    $errors['shippingData.' . $key] = $messages;
                }
                throw ValidationException::withMessages($errors);
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

