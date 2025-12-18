<?php

declare(strict_types=1);

namespace Molitor\Shop\Http\Livewire;

use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Illuminate\Support\Collection;
use Molitor\Order\Services\ShippingType;
use Molitor\Shop\Services\CheckoutService;

class ShippingMethodComponent extends Component
{
    public ?int $selectedShippingId = null;
    public array $shippingData = [];
    public array $shippingErrors = [];

    protected CheckoutService $checkoutService;

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
        CheckoutService $checkoutService
    ): void
    {
        $this->checkoutService = $checkoutService;
    }

    public function mount(): void
    {
        $this->selectedShippingId = $this->checkoutService->getShippingId();
        $this->shippingData = $this->checkoutService->getShippingData();
    }

    public function getShippingMethodsProperty(): Collection
    {
        return $this->checkoutService->getShippingMethods();
    }

    public function updatedSelectedShippingId($value): void
    {
        $this->selectedShippingId = (int)$value ?? null;
        $this->checkoutService->setShippingId($this->selectedShippingId);
        $this->shippingData = [];
        $this->shippingErrors = [];
    }

    public function handleShippingDataUpdate($data): void
    {
        $this->shippingData = $data;
    }

    public function getSelectedShippingTypeProperty(): ShippingType|null
    {
        return $this->checkoutService->getShippingType();
    }

    public function submit(): mixed
    {
        $this->validate();

        $this->shippingErrors = [];

        try {
            $this->shippingData = $this->checkoutService->validateShippingData($this->shippingData);
        } catch (ValidationException $e) {
            $this->shippingErrors = $e->errors();

            $errors = [];
            foreach ($e->errors() as $key => $messages) {
                $errors['shippingData.' . $key] = $messages;
            }
            throw ValidationException::withMessages($errors);
        }

        $this->checkoutService->setShippingId($this->selectedShippingId);
        $this->checkoutService->setShippingData($this->shippingData);
        $this->checkoutService->save();

        return redirect()->route('shop.checkout.payment');
    }

    public function render()
    {
        return view('shop::livewire.shipping-method-component');
    }
}

