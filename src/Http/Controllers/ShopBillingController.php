<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Shop\Http\Requests\BillingStepRequest;
use Molitor\Shop\Services\CheckoutService;

class ShopBillingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(): View|RedirectResponse
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

        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());

        /** @var CountryRepositoryInterface $countryRepository */
        $countryRepository = app(CountryRepositoryInterface::class);

        return view('shop::checkout.billing', [
            'customer' => $customer,
            'invoiceAddress' => $customer?->invoiceAddress,
            'countries' => $countryRepository->getAll(),
            'session' => $checkoutService->getCheckoutData(),
        ]);
    }

    public function store(BillingStepRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);

        // Billing address can be same as shipping
        $billingSame = (bool)($data['billing_same_as_shipping'] ?? false);
        $billing = [];

        if ($billingSame) {
            // Extract address from shipping_data if available
            $shippingData = $checkoutService->getShippingData();
            // For AddressShippingType, address is nested under 'address' key
            $billing = $shippingData['address'] ?? $shippingData;
        } else {
            $billing = $data['billing'];
        }

        $checkoutService->setBilling($billing);
        $checkoutService->setBillingSameAsShipping($billingSame);
        $checkoutService->save();

        return Redirect::route('shop.checkout.finalize');
    }
}

