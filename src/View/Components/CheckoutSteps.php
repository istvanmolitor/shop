<?php

namespace Molitor\Shop\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Molitor\Shop\Services\CheckoutService;

class CheckoutSteps extends Component
{
    /**
     * The current checkout step.
     */
    public string $current;

    /**
     * The steps configuration.
     */
    public array $steps;

    /**
     * The links for each step.
     */
    public array $links;

    /**
     * The current step number (for internal use).
     */
    public int $currentNumber;

    public function __construct(
        string $current = 'cart',
        array $links = []
    )
    {
        $this->current = $current;

        // Define all steps with their configuration
        $this->steps = [
            1 => ['key' => 'cart', 'label' => __('shop::common.checkout.steps.cart')],
            2 => ['key' => 'shipping', 'label' => __('shop::common.checkout.steps.shipping')],
            3 => ['key' => 'payment', 'label' => __('shop::common.checkout.steps.payment')],
            4 => ['key' => 'finalize', 'label' => __('shop::common.checkout.steps.finalize')],
        ];

        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);

        // Default routes for steps
        $defaultLinks = [
            'cart' => route('shop.cart.index'),
            'shipping' => $checkoutService->getShippingRoute(),
            'payment' => route('shop.checkout.payment'),
            'finalize' => route('shop.checkout.finalize'),
        ];

        $this->links = array_merge($defaultLinks, $links);

        // Find the current step number based on the key
        $this->currentNumber = $this->getCurrentStepNumber();
    }

    /**
     * Get the current step number based on the key.
     */
    private function getCurrentStepNumber(): int
    {
        foreach ($this->steps as $number => $step) {
            if ($step['key'] === $this->current) {
                return $number;
            }
        }

        return 1; // Default to first step if not found
    }

    /**
     * Check if a step is the current step.
     */
    public function isCurrent(int $number): bool
    {
        return $number === $this->currentNumber;
    }

    /**
     * Check if a step is completed.
     */
    public function isCompleted(int $number): bool
    {
        return $number < $this->currentNumber;
    }

    /**
     * Get the CSS classes for a step container.
     */
    public function getStepClasses(int $number): string
    {
        $baseClasses = 'flex items-center gap-3 p-3 border rounded transition';

        if ($this->isCurrent($number)) {
            return $baseClasses . ' border-emerald-600 bg-emerald-50 text-emerald-800';
        }

        if ($this->isCompleted($number)) {
            return $baseClasses . ' border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50';
        }

        return $baseClasses . ' border-gray-200 bg-white text-gray-500';
    }

    /**
     * Get the CSS classes for a step circle.
     */
    public function getCircleClasses(int $number): string
    {
        $baseClasses = 'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold';

        if ($this->isCurrent($number)) {
            return $baseClasses . ' bg-emerald-600 text-white';
        }

        if ($this->isCompleted($number)) {
            return $baseClasses . ' bg-emerald-100 text-emerald-700';
        }

        return $baseClasses . ' bg-gray-100 text-gray-500';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): ViewContract
    {
        return view('shop::components.checkout-steps');
    }
}

