<?php

namespace Molitor\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Molitor\Customer\Models\Customer;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Order\Models\Order;
use Molitor\Order\Models\OrderStatus;

class ShopCheckoutController extends BaseController
{
    public function show(): View
    {
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());

        $customer->load(['invoiceAddress', 'shippingAddress', 'currency']);

        return view('shop::checkout.index', [
            'customer' => $customer,
            'invoiceAddress' => $customer->invoiceAddress,
            'shippingAddress' => $customer->shippingAddress,
        ]);
    }

    public function store(): RedirectResponse
    {
        $customerRepository = app(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getByUser(Auth::user());

        request()->validate([
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $customer->loadMissing(['invoiceAddress', 'shippingAddress', 'currency']);

        // Determine default status
        $status = OrderStatus::query()->where('code', 'ordered')->first();
        if (!$status) {
            $status = OrderStatus::query()->firstOrFail();
        }

        /** @var Order $order */
        $order = Order::query()->create([
            'is_closed' => false,
            'customer_id' => $customer->id,
            'currency_id' => $customer->currency_id,
            'order_status_id' => $status->id,
            'invoice_address_id' => $customer->invoice_address_id,
            'shipping_address_id' => $customer->shipping_address_id,
            'comment' => request('comment'),
        ]);

        return Redirect::route('shop.products.index')
            ->with('status', __('Megrendelés létrehozva: :code', ['code' => (string)$order]));
    }
}
