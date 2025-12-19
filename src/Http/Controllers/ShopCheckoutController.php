<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Repositories\OrderPaymentRepositoryInterface;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
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
        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);

        try {
            $validated = $request->validated();
            $order = $checkoutService->createOrderFromRequest($validated, $request->input('comment'));

            return Redirect::route('shop.products.index')
                ->with('status', __('Megrendelés létrehozva: :code', ['code' => (string)$order]));
        } catch (\Exception $e) {
            return Redirect::back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
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

    public function placeOrder(CheckoutService $checkoutService): RedirectResponse
    {
        if (!$checkoutService->isValid()) {
            return Redirect::route('shop.checkout.shipping');
        }

        try {
            $order = $checkoutService->finalizeOrder(request()->input('comment'));

            return Redirect::route('shop.products.index')
                ->with('status', __('Megrendelés létrehozva: :code', ['code' => (string)$order]));
        } catch (\Exception $e) {
            return Redirect::route('shop.checkout.shipping')
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
