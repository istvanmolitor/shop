<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Repositories\OrderPaymentRepositoryInterface;
use Molitor\Shop\Http\Requests\PaymentStepRequest;
use Molitor\Shop\Services\CheckoutService;

class ShopPaymentController extends BaseController
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

        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());

        /** @var OrderPaymentRepositoryInterface $paymentRepository */
        $paymentRepository = app(OrderPaymentRepositoryInterface::class);
        $paymentMethods = $paymentRepository->getByShippingId($shippingId);

        return view('shop::checkout.payment', [
            'customer' => $customer,
            'paymentOptions' => $paymentRepository->getOptionsByShippingId($shippingId),
            'paymentMethods' => $paymentMethods,
            'session' => $checkoutService->getCheckoutData(),
        ]);
    }

    public function store(PaymentStepRequest $request): RedirectResponse
    {
        $data = $request->validated();

        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);

        $checkoutService->setPaymentId($data['order_payment_id']);
        $checkoutService->save();

        return Redirect::route('shop.checkout.invoice');
    }
}

