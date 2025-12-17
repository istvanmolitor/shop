<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Models\Customer;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Models\Order;
use Molitor\Order\Models\OrderStatus;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Order\Repositories\OrderPaymentRepositoryInterface;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
use Molitor\Address\Models\Address;
use Molitor\Shop\Http\Requests\CheckoutStoreRequest;
use Molitor\Shop\Http\Requests\ShippingStepRequest;
use Molitor\Shop\Http\Requests\PaymentStepRequest;

class ShopCheckoutController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(): View
    {
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());
        $customer?->loadMissing(['invoiceAddress', 'shippingAddress']);

        /** @var CountryRepositoryInterface $countryRepository */
        $countryRepository = app(CountryRepositoryInterface::class);
        /** @var OrderPaymentRepositoryInterface $paymentRepository */
        $paymentRepository = app(OrderPaymentRepositoryInterface::class);
        /** @var OrderShippingRepositoryInterface $shippingRepository */
        $shippingRepository = app(OrderShippingRepositoryInterface::class);

        return view('shop::checkout.index', [
            'customer' => $customer,
            'invoiceAddress' => $customer?->invoiceAddress,
            'shippingAddress' => $customer?->shippingAddress,
            'countries' => $countryRepository->getAll(),
            'paymentOptions' => $paymentRepository->getOptions(),
            'shippingOptions' => $shippingRepository->getOptions(),
        ]);
    }

    public function store(CheckoutStoreRequest $request): RedirectResponse
    {
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());

        $validated = $request->validated();

        $customer->loadMissing(['invoiceAddress', 'shippingAddress', 'currency']);

        // Determine default status
        $status = OrderStatus::query()->where('code', 'ordered')->first();
        if (!$status) {
            $status = OrderStatus::query()->firstOrFail();
        }

        // Update/Create invoice (billing) address on customer
        /** @var Address $invoiceAddress */
        $invoiceAddress = $customer->invoiceAddress;
        $invoiceAddress->fill($validated['billing']);
        $invoiceAddress->save();

        // Update/Create shipping address on customer
        $shippingSame = (bool)($validated['shipping_same_as_billing'] ?? false);
        /** @var Address $shippingAddress */
        $shippingAddress = $customer->shippingAddress;
        if ($shippingSame) {
            $shippingAddress->fill($validated['billing']);
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
            'comment' => $request->input('comment'),
        ]);

        return Redirect::route('shop.products.index')
            ->with('status', __('Megrendelés létrehozva: :code', ['code' => (string)$order]));
    }

    // --- Wizard flow ---
    public function redirectToWizard(): RedirectResponse
    {
        return Redirect::route('shop.checkout.shipping');
    }

    public function showShipping(CustomerRepositoryInterface $customerRepository): View
    {
        $customer = $customerRepository->getByUser(Auth::user());

        return view('shop::checkout.shipping', [
            'customer' => $customer,
        ]);
    }

    public function showPayment(): View|RedirectResponse
    {
        $checkout = session('checkout', []);
        $orderShippingId = (int)($checkout['order_shipping_id'] ?? 0);
        if ($orderShippingId <= 0) {
            return Redirect::route('shop.checkout.shipping');
        }

        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());
        $customer?->loadMissing(['invoiceAddress']);

        /** @var CountryRepositoryInterface $countryRepository */
        $countryRepository = app(CountryRepositoryInterface::class);
        /** @var OrderPaymentRepositoryInterface $paymentRepository */
        $paymentRepository = app(OrderPaymentRepositoryInterface::class);
        $paymentMethods = $paymentRepository->getByShippingId($orderShippingId);
        if ($paymentMethods->isEmpty()) {
            return Redirect::route('shop.checkout.shipping')
                ->with('status', __('Ehhez a szállítási módhoz jelenleg nincs elérhető fizetési mód. Kérjük, válasszon másik szállítási módot.'));
        }
        return view('shop::checkout.payment', [
            'customer' => $customer,
            'invoiceAddress' => $customer?->invoiceAddress,
            'countries' => $countryRepository->getAll(),
            // Keep options for potential uses, but pass only those allowed for the selected shipping
            'paymentOptions' => $paymentRepository->getOptionsByShippingId($orderShippingId),
            'paymentMethods' => $paymentMethods,
            'session' => $checkout,
        ]);
    }

    public function storePayment(PaymentStepRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $checkout = session('checkout', []);
        // Billing comes on payment step; allow using shipping as billing
        $billingSame = (bool)($data['billing_same_as_shipping'] ?? false);
        if ($billingSame) {
            // Extract address from shipping_data if available
            $shippingData = $checkout['shipping_data'] ?? [];
            // For AddressShippingType, address is nested under 'address' key
            $checkout['billing'] = $shippingData['address'] ?? $shippingData;
        } else {
            $checkout['billing'] = $data['billing'];
        }
        $checkout['billing_same_as_shipping'] = $billingSame;
        $checkout['order_payment_id'] = $data['order_payment_id'];
        session(['checkout' => $checkout]);
        return Redirect::route('shop.checkout.finalize');
    }

    public function showFinalize(): View|RedirectResponse
    {
        $checkout = session('checkout', []);
        if (!isset($checkout['order_shipping_id'])) {
            return Redirect::route('shop.checkout.shipping');
        }
        if (!isset($checkout['billing'], $checkout['order_payment_id'])) {
            return Redirect::route('shop.checkout.payment');
        }

        /** @var OrderPaymentRepositoryInterface $paymentRepository */
        $paymentRepository = app(OrderPaymentRepositoryInterface::class);
        /** @var OrderShippingRepositoryInterface $shippingRepository */
        $shippingRepository = app(OrderShippingRepositoryInterface::class);

        $paymentOptions = $paymentRepository->getOptions();
        $shippingOptions = $shippingRepository->getOptions();

        // Render shipping type view if available
        $shippingTypeView = null;
        if (isset($checkout['order_shipping_id'], $checkout['shipping_data'])) {
            /** @var \Molitor\Order\Models\OrderShipping $shipping */
            $shipping = \Molitor\Order\Models\OrderShipping::find($checkout['order_shipping_id']);
            if ($shipping && $shipping->type) {
                /** @var \Molitor\Order\Services\ShippingHandler $handler */
                $handler = app(\Molitor\Order\Services\ShippingHandler::class);
                $shippingType = $handler->getShippingType($shipping->type);
                if ($shippingType) {
                    $shippingTypeView = $shippingType->view($checkout['shipping_data'])->render();
                }
            }
        }

        return view('shop::checkout.finalize', [
            'data' => $checkout,
            'paymentLabel' => $paymentOptions[$checkout['order_payment_id']] ?? null,
            'shippingLabel' => $shippingOptions[$checkout['order_shipping_id']] ?? null,
            'shippingTypeView' => $shippingTypeView,
        ]);
    }

    public function placeOrder(): RedirectResponse
    {
        $checkout = session('checkout', []);
        if (!isset($checkout['billing'], $checkout['order_payment_id'], $checkout['order_shipping_id'])) {
            return Redirect::route('shop.checkout.shipping');
        }

        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());
        $customer->loadMissing(['invoiceAddress', 'shippingAddress', 'currency']);

        // Determine default status
        $status = OrderStatus::query()->where('code', 'ordered')->first();
        if (!$status) {
            $status = OrderStatus::query()->firstOrFail();
        }

        /** @var Address $invoiceAddress */
        $invoiceAddress = $customer->invoiceAddress;
        $invoiceAddress->fill($checkout['billing']);
        $invoiceAddress->save();

        /** @var Address $shippingAddress */
        $shippingAddress = $customer->shippingAddress;
        // Extract shipping address from shipping_data if available
        $shippingData = $checkout['shipping_data'] ?? [];
        // For AddressShippingType, address is nested under 'address' key
        $shippingAddressData = $shippingData['address'] ?? $shippingData;
        $shippingAddress->fill($shippingAddressData);
        $shippingAddress->save();

        /** @var Order $order */
        $order = Order::query()->create([
            'is_closed' => false,
            'customer_id' => $customer->id,
            'currency_id' => $customer->currency_id,
            'order_status_id' => $status->id,
            'order_payment_id' => $checkout['order_payment_id'],
            'order_shipping_id' => $checkout['order_shipping_id'],
            'invoice_address_id' => $invoiceAddress->id,
            'shipping_address_id' => $shippingAddress->id,
            'shipping_data' => $checkout['shipping_data'] ?? null,
            // Comment will be posted on finalize step; keep backward-compat with any session-stored value
            'comment' => request()->input('comment', $checkout['comment'] ?? null),
        ]);

        // Clear checkout session
        session()->forget('checkout');

        return Redirect::route('shop.products.index')
            ->with('status', __('Megrendelés létrehozva: :code', ['code' => (string)$order]));
    }
}
