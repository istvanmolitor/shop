<?php

namespace Molitor\Shop\Services;

use Molitor\Order\Services\ShippingHandler;
use Molitor\Order\Services\ShippingType;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;

class CheckoutService
{
    const SESSION_KEY = 'checkout';
    private int|null $shippingId = null;
    private array $shippingData = [];
    private ShippingHandler $shippingHandler;
    private OrderShippingRepositoryInterface $shippingRepository;

    public function __construct(
        ShippingHandler $shippingHandler,
        OrderShippingRepositoryInterface $shippingRepository
    )
    {
        $this->shippingHandler = $shippingHandler;
        $this->shippingRepository = $shippingRepository;
        $this->update();
    }

    public function update(): void
    {
        $checkout = session(static::SESSION_KEY, []);

        $this->shippingId = isset($checkout['order_shipping_id']) ? (int)$checkout['order_shipping_id'] : null;
        $this->shippingData = $checkout['shipping_data'] ?? [];
    }

    public function getShippingId(): int|null
    {
        return $this->shippingId;
    }

    public function getShippingData(): array
    {
        return $this->shippingData;
    }

    public function setShippingId(int $shippingId): void
    {
        $this->shippingId = $shippingId;
    }

    public function setShippingData(array $shippingData): void
    {
        $this->shippingData = $shippingData;
    }

    public function save(): void
    {
        $checkout = session(static::SESSION_KEY, []);
        $checkout['order_shipping_id'] = $this->shippingId;
        $checkout['shipping_data'] = $this->shippingData;
        session([static::SESSION_KEY => $checkout]);
    }

    public function getCheckoutData(): array
    {
        return session(static::SESSION_KEY, []);
    }

    public function getShippingMethods()
    {
        return $this->shippingRepository->getAll();
    }

    public function getShippingType(): ShippingType|null
    {
        if (!$this->shippingId) {
            return null;
        }

        $shippingMethod = $this->shippingRepository->getAll()->firstWhere('id', $this->shippingId);

        if (!$shippingMethod || !$shippingMethod->type) {
            return null;
        }

        return $this->shippingHandler->getShippingType($shippingMethod->type);
    }

    public function validateShippingData(array $shippingData): array
    {
        $shippingType = $this->getShippingType();

        if (!$shippingType) {
            return $shippingData;
        }

        return $shippingType->validate($shippingData);
    }
}
