<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Models\OrderShipping;
use Molitor\Order\Repositories\OrderShippingRepositoryInterface;
use Molitor\Order\Services\ShippingHandler;
use Molitor\Shop\Services\CheckoutService;

class ShopShippingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(CustomerRepositoryInterface $customerRepository, OrderShippingRepositoryInterface $shippingRepository, CheckoutService $checkoutService): View
    {
        $customer = $customerRepository->getByUser(Auth::user());
        $shippingMethods = $shippingRepository->getAll();

        $selectedShipping = null;
        $shippingId = $checkoutService->getShippingId();
        if ($shippingId) {
            $selectedShipping = $shippingMethods->firstWhere('id', $shippingId);
        }

        return view('shop::checkout.shipping', [
            'customer' => $customer,
            'shippingMethods' => $shippingMethods,
            'selectedShipping' => $selectedShipping,
            'shippingType' => null,
            'shippingForm' => null
        ]);
    }

    public function show(
        OrderShipping $shipping,
        CustomerRepositoryInterface $customerRepository,
        OrderShippingRepositoryInterface $shippingRepository,
        ShippingHandler $shippingHandler,
        CheckoutService $checkoutService
    ): View
    {
        $customer = $customerRepository->getByUser(Auth::user());
        $shippingMethods = $shippingRepository->getAll();

        $shippingType = $shippingHandler->getShippingType($shipping->type);
        if(!$shippingType) {
            abort(404);
        }

        if($shipping->id === $checkoutService->getShippingId()) {
            $defaultValues = $checkoutService->getShippingData();
        }
        else {
            $defaultValues = $shippingType->getDefaultValues();
        }

        $formTemplate = $shippingType->getFormTemplate();
        $formTemplateData = array_merge([
            'shipping' => $shipping,
            'customer' => $customer,
            'data' => $defaultValues,
        ], $shippingType->getFormTemplateData());

        return view('shop::checkout.shipping', [
            'customer' => $customer,
            'action' => $shippingType->getAction() ?? route('shop.checkout.shipping.store', [$shipping]),
            'shippingMethods' => $shippingMethods,
            'selectedShipping' => $shipping,
            'shippingType' => $shippingType,
            'shippingForm' => view($formTemplate, $formTemplateData)->render(),
        ]);
    }

    public function store(OrderShipping $shipping, Request $request, ShippingHandler $shippingHandler): RedirectResponse
    {
        $shippingType = $shippingHandler->getShippingType($shipping->type);
        if(!$shippingType) {
            abort(404);
        }

        $data = $request->validate($shippingType->validationRules($request->all()));

        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);
        $checkoutService->setShipping($shipping->id, $data);

        return Redirect::route('shop.checkout.payment');
    }
}

