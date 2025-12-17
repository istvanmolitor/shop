<?php

namespace Molitor\Shop\View\Components;

use Illuminate\View\Component;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
use Molitor\Order\Services\ShippingHandler;

class ShippingMethodForm extends Component
{
    public $shippingMethods;
    public $shippingHandler;
    public $session;
    public $cartRoute;
    public $submitRoute;
    public $submitButtonText;
    public $backButtonText;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $cartRoute = null,
        ?string $submitRoute = null,
        ?string $submitButtonText = null,
        ?string $backButtonText = null
    ) {
        /** @var OrderShippingRepositoryInterface $shippingRepository */
        $shippingRepository = app(OrderShippingRepositoryInterface::class);
        $this->shippingMethods = $shippingRepository->getAll();

        $this->shippingHandler = app(ShippingHandler::class);
        $this->session = session('checkout', []);

        $this->cartRoute = $cartRoute ?? 'shop.cart.index';
        $this->submitRoute = $submitRoute ?? 'shop.checkout.shipping.store';
        $this->submitButtonText = $submitButtonText;
        $this->backButtonText = $backButtonText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('shop::components.shipping-method-form');
    }
}

