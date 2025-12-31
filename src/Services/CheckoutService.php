<?php

namespace Molitor\Shop\Services;

use Illuminate\Support\Facades\Auth;
use Molitor\Address\Models\Address;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Customer\Models\Customer;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Models\Order;
use Molitor\Order\Models\OrderPayment;
use Molitor\Order\Models\OrderShipping;
use Molitor\Order\Models\OrderStatus;
use Molitor\Order\Repositories\OrderPaymentRepositoryInterface;
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

    public function getOrderPayment(): OrderPayment|null
    {
        if(!$this->paymentId) {
            return null;
        }
        /** @var OrderPaymentRepositoryInterface $paymentRepository */
        $paymentRepository = app(OrderPaymentRepositoryInterface::class);
        return $paymentRepository->getById($this->paymentId);
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
        return $this->invoice ?? [
            'name' => '',
            'country_id' => app(CountryRepositoryInterface::class)->getDefault()->id,
            'zip_code' => '',
            'city' => '',
            'address' => '',
        ];
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

    public function createInvoiceAddress(): Address
    {
        $invoiceData = $this->getInvoice();

        $address = new Address();
        $address->fill($invoiceData);
        $address->save();

        return $address;
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
        return $this->isCartReady() && $this->isShippingReady() && $this->isPaymentReady() && $this->isInvoiceReady();
    }



    /**
     * Create order from checkout session data
     *
     * @param string|null $comment Optional comment for the order
     * @return Order The created order
     * @throws \Exception If order creation fails
     */
    public function store(?string $comment = null): Order
    {
        if(!$this->isValid()) {
            throw new \Exception('Invalid checkout data');
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

        // Create invoice address
        $invoiceAddress = $this->createInvoiceAddress();

        // Update/Create shipping address on customer
        /** @var Address $shippingAddress */
        $shippingAddress = $customer->shippingAddress;
        if ($this->getInvoiceSameAsShipping()) {
            $shippingAddress->fill($this->getInvoice());
        } else {
            $shippingAddress->fill($this->getShippingData());
        }
        $shippingAddress->save();

        /** @var Order $order */
        $order = Order::query()->create([
            'is_closed' => false,
            'customer_id' => $customer->id,
            'currency_id' => $customer->currency_id,
            'order_status_id' => $status->id,
            'order_payment_id' => $this->getPaymentId(),
            'order_shipping_id' => $this->getShippingId(),
            'invoice_address_id' => $invoiceAddress->id,
            'shipping_address_id' => $shippingAddress->id,
            'comment' => $comment,
        ]);

        return $order;
    }
}
