<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Repositories\OrderPaymentRepositoryInterface;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
use Molitor\Order\Models\OrderPayment;
use Molitor\Shop\Http\Requests\ShippingStepRequest;

class ShopShippingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CustomerRepositoryInterface $customerRepository, OrderPaymentRepositoryInterface $paymentRepository): View
    {
        $customer = $customerRepository->getByUser(Auth::user());
        $paymentMethods = $paymentRepository->getAll();

        return view('shop::checkout.shipping', [
            'customer' => $customer,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function show(
        OrderPayment $payment,
        CustomerRepositoryInterface $customerRepository,
        OrderPaymentRepositoryInterface $paymentRepository,
        OrderShippingRepositoryInterface $shippingRepository
    ): View
    {
        $customer = $customerRepository->getByUser(Auth::user());
        $paymentMethods = $paymentRepository->getAll();

        $shippingMethods = $shippingRepository->getByPaymentId($payment->id);

        return view('shop::checkout.shipping', [
            'customer' => $customer,
            'paymentMethods' => $paymentMethods,
            'selectedPayment' => $payment,
            'shippingMethods' => $shippingMethods,
        ]);
    }

    public function store(ShippingStepRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $checkout = session('checkout', []);
        $checkout['order_payment_id'] = $data['order_payment_id'];
        $checkout['order_shipping_id'] = $data['order_shipping_id'];
        $checkout['shipping_data'] = $data['shipping_data'] ?? null;
        session(['checkout' => $checkout]);

        return Redirect::route('shop.checkout.payment');
    }
}

