<?php

namespace Molitor\Shop\Services;

class CheckoutService
{
    private int|null $shippingId = null;
    private array $shippingData = [];

    public function __construct()
    {
        $this->update();
    }

    public function update(): void
    {
        $checkout = session('checkout', []);

        $this->shippingId = (int)($checkout['order_shipping_id'] ?? 0);
    }

    public function getShippingId(): int|null
    {
        return $this->shippingId;
    }
}
