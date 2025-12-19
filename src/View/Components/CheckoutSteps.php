<?php

namespace Molitor\Shop\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View as ViewContract;
use Molitor\Shop\Services\CheckoutService;

class CheckoutSteps extends Component
{
    const STEP_CART = 'cart';
    const STEP_SHIPPING = 'shipping';
    const STEP_PAYMENT = 'payment';
    const STEP_INVOICE = 'invoice';
    const STEP_FINALIZE = 'finalize';

    /**
     * The current checkout step.
     */
    public string $current;

    /**
     * The links for each step.
     */
    public array $links;

    /**
     * The current step number (for internal use).
     */
    public int $currentNumber;

    /**
     * The base steps configuration.
     */
    private array $baseSteps = [
        self::STEP_CART => ['number' => 1, 'label' => 'shop::common.checkout.steps.cart'],
        self::STEP_SHIPPING => ['number' => 2, 'label' => 'shop::common.checkout.steps.shipping'],
        self::STEP_PAYMENT => ['number' => 3, 'label' => 'shop::common.checkout.steps.payment'],
        self::STEP_INVOICE => ['number' => 4, 'label' => 'shop::common.checkout.steps.invoice'],
        self::STEP_FINALIZE => ['number' => 5, 'label' => 'shop::common.checkout.steps.finalize'],
    ];

    public function __construct(
        private CheckoutService $checkoutService,
        string $current = 'cart'
    )
    {
        $this->current = $current;
        $this->currentNumber = $this->getCurrentStepNumber();

        $this->links = [
            self::STEP_CART => route('shop.cart.index'),
            self::STEP_SHIPPING => $this->checkoutService->getShippingRoute(),
            self::STEP_PAYMENT => route('shop.checkout.payment'),
            self::STEP_INVOICE => route('shop.checkout.invoice'),
            self::STEP_FINALIZE => route('shop.checkout.finalize'),
        ];
    }

    /**
     * Get the current step number based on the key.
     */
    private function getCurrentStepNumber(): int
    {
        if (isset($this->baseSteps[$this->current])) {
            return $this->baseSteps[$this->current]['number'];
        }

        return 1;
    }

    /**
     * Get the steps configuration for the template.
     */
    public function getSteps(): array
    {
        $steps = [];

        foreach ($this->baseSteps as $stepName => $step) {
            $steps[$stepName] = [
                'number' => $step['number'],
                'label' => __($step['label']),
                'is_completed' => $this->isCompleted($stepName),
                'is_current' => $this->isCurrent($stepName),
                'link' => $this->links[$stepName] ?? '#',
            ];
        }

        return $steps;
    }

    public function getNumberByStep($stepName): int|null
    {
        return $this->baseSteps[$stepName]['number'] ?? null;
    }

    /**
     * Check if a step is the current step.
     */
    public function isCurrent(string $stepName): bool
    {
        return $stepName === $this->current;
    }

    /**
     * Check if a step is completed.
     */
    public function isCompleted(string $stepName): bool
    {
        if($stepName === self::STEP_SHIPPING) {
            return $this->checkoutService->isCartReady();
        }
        elseif($stepName === self::STEP_PAYMENT) {
            return $this->checkoutService->isShippingReady();
        }
        elseif($stepName === self::STEP_INVOICE) {
            return $this->checkoutService->isPaymentReady();
        }
        elseif($stepName === self::STEP_FINALIZE) {
            return $this->checkoutService->isInvoiceReady();
        }
        return true;
    }

    /**
     * Get the CSS classes for a step container.
     */
    public function getStepClasses(string $stepName): string
    {
        if (!isset($this->baseSteps[$stepName])) {
            return 'flex items-center gap-3 p-3 border rounded transition border-gray-200 bg-white text-gray-500';
        }

        $baseClasses = 'flex items-center gap-3 p-3 border rounded transition';

        if ($this->isCurrent($stepName)) {
            return $baseClasses . ' border-emerald-600 bg-emerald-50 text-emerald-800';
        }

        if ($this->isCompleted($stepName)) {
            return $baseClasses . ' border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50';
        }

        return $baseClasses . ' border-gray-200 bg-white text-gray-500';
    }

    /**
     * Get the CSS classes for a step circle.
     */
    public function getCircleClasses(string $stepName): string
    {
        if (!isset($this->baseSteps[$stepName])) {
            return 'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold bg-gray-100 text-gray-500';
        }

        $baseClasses = 'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold';

        if ($this->isCurrent($stepName)) {
            return $baseClasses . ' bg-emerald-600 text-white';
        }

        if ($this->isCompleted($stepName)) {
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

