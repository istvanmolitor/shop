<?php

namespace Molitor\Shop\Services;

use Illuminate\Support\Facades\Auth;
use Molitor\Address\Models\Address;
use Molitor\Customer\Models\Customer;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Models\Order;
use Molitor\Order\Models\OrderShipping;
use Molitor\Order\Models\OrderStatus;
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

    public function __construct(
        private CartService $cartService,
        private ShippingHandler $shippingHandler,
        private OrderShippingRepositoryInterface $shippingRepository
    )
    {
        $this->update();
    }

    public function getCheckoutData(): array
    {
        return session(static::SESSION_KEY, []);
    }

    public function save(): void
    {
        $checkout = $this->getCheckoutData();
        $checkout['order_shipping_id'] = $this->shippingId;
        $checkout['shipping_data'] = $this->shippingData;
        $checkout['order_payment_id'] = $this->paymentId;
        $checkout['invoice'] = $this->invoice;
        $checkout['invoice_same_as_shipping'] = $this->invoiceSameAsShipping;
        session([static::SESSION_KEY => $checkout]);
    }

    public function update(): void
    {
        $checkout = $this->getCheckoutData();
        $this->shippingId = isset($checkout['order_shipping_id']) ? (int)$checkout['order_shipping_id'] : null;
        $this->shippingData = $checkout['shipping_data'] ?? [];
        $this->paymentId = isset($checkout['order_payment_id']) ? (int)$checkout['order_payment_id'] : null;
        $this->invoice = $checkout['invoice'] ?? [];
        $this->invoiceSameAsShipping = (bool)($checkout['invoice_same_as_shipping'] ?? false);
    }

    /*Cart************************************************/

    public function isCartReady(): bool
    {
        return $this->cartService->count() > 0;
    }

    /*Shipping************************************************/

    public function isShippingReady(): bool
    {
        return $this->shippingId !== null;
    }

    public function setShippingId(int $shippingId): void
    {
        $this->shippingId = $shippingId;
    }

    public function getShippingId(): int|null
    {
        return $this->shippingId;
    }

    public function setShippingData(array $shippingData): void
    {
        $this->shippingData = $shippingData;
    }

    public function getShippingData(): array
    {
        return $this->shippingData;
    }

    public function getShippingMethods()
    {
        return $this->shippingRepository->getAll();
    }

    public function getOrderShipping(): OrderShipping|null
    {
        if(!$this->shippingId) {
            return null;
        }
        return $this->shippingRepository->getById($this->shippingId);
    }

    public function getShippingType(): ShippingType|null
    {
        $orderShipping = $this->getOrderShipping();

        if (!$orderShipping || !$orderShipping->type) {
            return null;
        }

        return $this->shippingHandler->getShippingType($orderShipping->type);
    }

    public function saveShipping(int $id, array $data): void
    {
        $this->shippingId = $id;
        $this->shippingData = $data;
        $this->save();
    }

    /*Payment************************************************/

    public function isPaymentReady(): bool
    {
        return $this->paymentId !== null;
    }

    public function getPaymentId(): int|null
    {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function savePayment(int $paymentId): void
    {
        $this->setPaymentId($paymentId);
        $this->save();
    }

    /*Invoice************************************************/

    public function isInvoiceReady(): bool
    {
        return $this->invoice !== [];
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

    /*************************************************/

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

    /*Finalize************************************************/

    public function isValid(): bool
    {
        return !$this->shippingId || !$this->paymentId || empty($this->invoice);
    }

    /**
     * Finalize the order by creating it in the database
     *
     * @param string|null $comment Optional comment for the order
     * @return Order The created order
     * @throws \Exception If required checkout data is missing
     */
    public function finalizeOrder(?string $comment = null): Order
    {
        if (!$this->isValid()) {
            throw new \Exception('Incomplete checkout data. Cannot finalize order.');
        }

        /** @var CustomerRepositoryInterface $customerRepository */
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());
        $customer->loadMissing(['invoiceAddress', 'shippingAddress', 'currency']);

        // Determine default status
        $status = OrderStatus::query()->where('code', 'ordered')->first();
        if (!$status) {
            $status = OrderStatus::query()->firstOrFail();
        }

        // Update invoice address
        /** @var Address $invoiceAddress */
        $invoiceAddress = $customer->invoiceAddress;
        $invoiceAddress->fill($this->invoice);
        $invoiceAddress->save();

        // Update shipping address
        /** @var Address $shippingAddress */
        $shippingAddress = $customer->shippingAddress;
        // For AddressShippingType, address is nested under 'address' key
        $shippingAddressData = $this->shippingData['address'] ?? $this->shippingData;
        $shippingAddress->fill($shippingAddressData);
        $shippingAddress->save();

        // Create the order
        /** @var Order $order */
        $order = Order::query()->create([
            'is_closed' => false,
            'customer_id' => $customer->id,
            'currency_id' => $customer->currency_id,
            'order_status_id' => $status->id,
            'order_payment_id' => $this->paymentId,
            'order_shipping_id' => $this->shippingId,
            'invoice_address_id' => $invoiceAddress->id,
            'shipping_address_id' => $shippingAddress->id,
            'shipping_data' => $this->shippingData ?: null,
            'comment' => $comment ?? $this->getCheckoutData()['comment'] ?? null,
        ]);

        return $order;
    }

    /**
     * Create order directly from validated request data (non-wizard flow)
     *
     * @param array $validated Validated request data
     * @param string|null $comment Optional comment for the order
     * @return Order The created order
     * @throws \Exception If order creation fails
     */
    public function createOrderFromRequest(array $validated, ?string $comment = null): Order
    {
        /** @var CustomerRepositoryInterface $customerRepository */
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());
        $customer->loadMissing(['invoiceAddress', 'shippingAddress', 'currency']);

        // Determine default status
        $status = OrderStatus::query()->where('code', 'ordered')->first();
        if (!$status) {
            $status = OrderStatus::query()->firstOrFail();
        }

        // Update/Create invoice address on customer
        /** @var Address $invoiceAddress */
        $invoiceAddress = $customer->invoiceAddress;
        $invoiceAddress->fill($validated['invoice']);
        $invoiceAddress->save();

        // Update/Create shipping address on customer
        $shippingSame = (bool)($validated['shipping_same_as_invoice'] ?? false);
        /** @var Address $shippingAddress */
        $shippingAddress = $customer->shippingAddress;
        if ($shippingSame) {
            $shippingAddress->fill($validated['invoice']);
        } else {
            $shippingAddress->fill($validated['shipping'] ?? []);
        }
        $shippingAddress->save();

        /** @var Order $order */
        $order = Order::query()->create([
            'is_closed' => false,
            'customer_id' => $customer->id,
            'currency_id' => $customer->currency_id,
            'order_status_id' => $status->id,
            'order_payment_id' => $validated['order_payment_id'],
            'order_shipping_id' => $validated['order_shipping_id'],
            'invoice_address_id' => $invoiceAddress->id,
            'shipping_address_id' => $shippingAddress->id,
            'comment' => $comment,
        ]);

        return $order;
    }
}
