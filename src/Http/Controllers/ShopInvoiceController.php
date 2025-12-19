<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Shop\Http\Requests\InvoiceStepRequest;
use Molitor\Shop\Services\CheckoutService;

class ShopInvoiceController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(): View|RedirectResponse
    {
        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);
        if(!$checkoutService->isPaymentReady()) {
            return Redirect::route('shop.checkout.payment');
        }

        /** @var CountryRepositoryInterface $countryRepository */
        $countryRepository = app(CountryRepositoryInterface::class);

        return view('shop::checkout.invoice', [
            'countries' => $countryRepository->getOptions(),
            'defaultCountryId' => $countryRepository->getDefaultId(),
            'invoice' => $checkoutService->getInvoice(),
        ]);
    }

    public function store(InvoiceStepRequest $request): RedirectResponse
    {
        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);
        if(!$checkoutService->isPaymentReady()) {
            return Redirect::route('shop.checkout.payment');
        }

        $data = $request->validated();

        $invoiceSame = (bool)($data['invoice_same_as_shipping'] ?? false);

        $checkoutService->setInvoice([
            'name' => $data['name'],
            'country_id' => $data['country_id'],
            'zip_code' => $data['zip_code'],
            'city' => $data['city'],
            'address' => $data['address'],
        ]);
        $checkoutService->setInvoiceSameAsShipping($invoiceSame);
        $checkoutService->save();

        return Redirect::route('shop.checkout.finalize');
    }
}

