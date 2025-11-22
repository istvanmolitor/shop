<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            ->with(['orderItems.product', 'orderStatus', 'invoiceAddress', 'shippingAddress'])
            ->where('code', $code)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$order) {
            throw (new ModelNotFoundException())->setModel(Order::class);
        }

        return view('shop::orders.show', [
            'order' => $order,
        ]);
    }
}
