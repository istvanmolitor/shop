<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Models\Order;

class ShopOrderController extends BaseController
{
    public function index(Request $request): View
    {
        /** @var CustomerRepositoryInterface $customerRepository */
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser($request->user());

        $orders = Order::query()
            ->with(['orderStatus'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('id')
            ->paginate(20);

        return view('shop::orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, string $code): View|RedirectResponse
    {
        /** @var CustomerRepositoryInterface $customerRepository */
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser($request->user());

        $order = Order::query()
            ->with([
                'orderItems.product.productImages',
                'orderItems.currency',
                'orderStatus',
                'invoiceAddress.country',
                'shippingAddress.country',
                'orderPayment',
                'orderShipping',
            ])
            ->where('code', $code)
            ->where('customer_id', $customer->id)
            ->first();

        if (! $order) {
            throw (new ModelNotFoundException)->setModel(Order::class);
        }

        return view('shop::orders.show', [
            'order' => $order,
        ]);
    }
}
