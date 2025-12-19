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
    private CheckoutService $checkoutService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->checkoutService = app(CheckoutService::class);
    }

    public function index(CustomerRepositoryInterface $customerRepository, OrderShippingRepositoryInterface $shippingRepository): View|RedirectResponse
    {
        if(!$this->checkoutService->isCartReady()) {
            return Redirect::route('shop.cart.index');
        }

        return view('shop::checkout.shipping', [
            'shippingMethods' => $shippingRepository->getAll(),
            'selectedShipping' => $this->checkoutService->getOrderShipping(),
            'shippingType' => null,
            'shippingForm' => null
        ]);
    }

    public function show(
        OrderShipping $shipping,
        OrderShippingRepositoryInterface $shippingRepository,
        ShippingHandler $shippingHandler,
        CheckoutService $checkoutService
    ): View|RedirectResponse
    {
        if(!$this->checkoutService->isCartReady()) {
            return Redirect::route('shop.cart.index');
        }

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
            'data' => $defaultValues,
        ], $shippingType->getFormTemplateData());

        return view('shop::checkout.shipping', [
            'action' => $shippingType->getAction() ?? route('shop.checkout.shipping.store', [$shipping]),
            'shippingMethods' => $shippingMethods,
            'selectedShipping' => $shipping,
            'shippingType' => $shippingType,
            'shippingForm' => view($formTemplate, $formTemplateData)->render(),
        ]);
    }

    public function store(OrderShipping $shipping, Request $request, ShippingHandler $shippingHandler): RedirectResponse
    {
        if(!$this->checkoutService->isCartReady()) {
            return Redirect::route('shop.cart.index');
        }

        $shippingType = $shippingHandler->getShippingType($shipping->type);
        if(!$shippingType) {
            abort(404);
        }

        $data = $request->validate($shippingType->validationRules($request->all()));

        /** @var CheckoutService $checkoutService */
        $checkoutService = app(CheckoutService::class);
        $checkoutService->saveShipping($shipping->id, $data);

        return Redirect::route('shop.checkout.payment');
    }
}

