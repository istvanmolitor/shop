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
    private int|null $paymentId = null;
    private array $invoice = [];
    private bool $invoiceSameAsShipping = false;
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

    public function save(): void
    {
        $checkout = session(static::SESSION_KEY, []);
        $checkout['order_shipping_id'] = $this->shippingId;
        $checkout['shipping_data'] = $this->shippingData;
        $checkout['order_payment_id'] = $this->paymentId;
        $checkout['invoice'] = $this->invoice;
        $checkout['invoice_same_as_shipping'] = $this->invoiceSameAsShipping;
        session([static::SESSION_KEY => $checkout]);
    }

    public function update(): void
    {
        $checkout = session(static::SESSION_KEY, []);

        $this->shippingId = isset($checkout['order_shipping_id']) ? (int)$checkout['order_shipping_id'] : null;
        $this->shippingData = $checkout['shipping_data'] ?? [];
        $this->paymentId = isset($checkout['order_payment_id']) ? (int)$checkout['order_payment_id'] : null;
        $this->invoice = $checkout['invoice'] ?? [];
        $this->invoiceSameAsShipping = (bool)($checkout['invoice_same_as_shipping'] ?? false);
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

    public function setShipping(int $id, array $data): void
    {
        $this->shippingId = $id;
        $this->shippingData = $data;
        $this->save();
    }

    public function getPaymentId(): int|null
    {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function getInvoice(): array
    {
        return $this->invoice;
    }

    public function setInvoice(array $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function getInvoiceSameAsShipping(): bool
    {
        return $this->invoiceSameAsShipping;
    }

    public function setInvoiceSameAsShipping(bool $invoiceSameAsShipping): void
    {
        $this->invoiceSameAsShipping = $invoiceSameAsShipping;
    }

    public function setPayment(int $paymentId, array $invoice, bool $invoiceSameAsShipping): void
    {
        $this->paymentId = $paymentId;
        $this->invoice = $invoice;
        $this->invoiceSameAsShipping = $invoiceSameAsShipping;
        $this->save();
    }

    public function getShippingRoute(): string
    {
        if($this->shippingId) {
            $shipping = $this->shippingRepository->getById($this->shippingId);
            if($shipping) {
                return route('shop.checkout.shipping.show', $shipping);
            }
        }
        return route('shop.checkout.shipping');
    }
}
