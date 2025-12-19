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
use Molitor\Shop\Services\CheckoutService;

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


    public function showFinalize(): View|RedirectResponse
    {
        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);

        $shippingId = $checkoutService->getShippingId();
        if (!$shippingId) {
            return Redirect::route('shop.checkout.shipping');
        }

        $paymentId = $checkoutService->getPaymentId();
        if (!$paymentId) {
            return Redirect::route('shop.checkout.payment');
        }

        $invoice = $checkoutService->getInvoice();
        if (empty($invoice)) {
            return Redirect::route('shop.checkout.invoice');
        }

        /** @var OrderPaymentRepositoryInterface $paymentRepository */
        $paymentRepository = app(OrderPaymentRepositoryInterface::class);
        /** @var OrderShippingRepositoryInterface $shippingRepository */
        $shippingRepository = app(OrderShippingRepositoryInterface::class);

        $paymentOptions = $paymentRepository->getOptions();
        $shippingOptions = $shippingRepository->getOptions();

        // Render shipping type view if available
        $shippingTypeView = null;

        // Get cart items and total
        /** @var \Molitor\Shop\Services\CartService $cartService */
        $cartService = app(\Molitor\Shop\Services\CartService::class);
        $cartItems = $cartService->getItems();
        $cartTotal = $cartService->getTotal();

        $checkout = $checkoutService->getCheckoutData();
        return view('shop::checkout.finalize', [
            'data' => $checkout,
            'paymentLabel' => $paymentOptions[$paymentId] ?? null,
            'shippingLabel' => $shippingOptions[$shippingId] ?? null,
            'shippingTypeView' => $shippingTypeView,
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
        ]);
    }

    public function placeOrder(): RedirectResponse
    {
        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);

        $shippingId = $checkoutService->getShippingId();
        $paymentId = $checkoutService->getPaymentId();
        $invoice = $checkoutService->getInvoice();

        if (!$shippingId || !$paymentId || empty($invoice)) {
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
        $invoiceAddress->fill($invoice);
        $invoiceAddress->save();

        /** @var Address $shippingAddress */
        $shippingAddress = $customer->shippingAddress;
        // Extract shipping address from shipping_data if available
        $shippingData = $checkoutService->getShippingData();
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
            'order_payment_id' => $paymentId,
            'order_shipping_id' => $shippingId,
            'invoice_address_id' => $invoiceAddress->id,
            'shipping_address_id' => $shippingAddress->id,
            'shipping_data' => $shippingData ?: null,
            // Comment will be posted on finalize step; keep backward-compat with any session-stored value
            'comment' => request()->input('comment', $checkoutService->getCheckoutData()['comment'] ?? null),
        ]);

        // Clear checkout session
        session()->forget('checkout');

        return Redirect::route('shop.products.index')
            ->with('status', __('Megrendelés létrehozva: :code', ['code' => (string)$order]));
    }
}
