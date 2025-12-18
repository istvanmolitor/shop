<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Models\OrderShipping;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
use Molitor\Order\Services\ShippingHandler;
use Molitor\Shop\Http\Requests\ShippingStepRequest;

class ShopShippingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CustomerRepositoryInterface $customerRepository, OrderShippingRepositoryInterface $shippingRepository): View
    {
        $customer = $customerRepository->getByUser(Auth::user());
        $shippingMethods = $shippingRepository->getAll();

        return view('shop::checkout.shipping', [
            'customer' => $customer,
            'shippingMethods' => $shippingMethods,
            'selectedShipping' => null,
        ]);
    }

    public function show(
        OrderShipping $shipping,
        CustomerRepositoryInterface $customerRepository,
        OrderShippingRepositoryInterface $shippingRepository,
        ShippingHandler $shippingHandler
    ): View
    {
        $customer = $customerRepository->getByUser(Auth::user());
        $shippingMethods = $shippingRepository->getAll();

        $shippingType = $shippingHandler->getShippingType($shipping->type);
        if(!$shippingType) {
            abort(404);
        }

        $formTemplate = $shippingType->getFormTemplate();
        $formTemplateData = array_merge([
            'action' => $shippingType->getAction() ?? route('shop.checkout.shipping.store'),
            'shipping' => $shipping,
            'customer' => $customer,
        ], $shippingType->getFormTemplateData());

        return view('shop::checkout.shipping', [
            'customer' => $customer,
            'shippingMethods' => $shippingMethods,
            'selectedShipping' => $shipping,
            'shippingForm' => view($formTemplate, $formTemplateData)->render(),
        ]);
    }

    public function store(ShippingStepRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $checkout = session('checkout', []);
        $checkout['order_shipping_id'] = $data['order_shipping_id'];
        $checkout['shipping_data'] = $data['shipping_data'] ?? null;
        session(['checkout' => $checkout]);

        return Redirect::route('shop.checkout.shipping');
    }
}

