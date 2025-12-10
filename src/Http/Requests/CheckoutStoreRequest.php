<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'billing.name' => ['required', 'string', 'max:255'],
            'billing.country_id' => ['required', 'integer', 'exists:countries,id'],
            'billing.zip_code' => ['required', 'string', 'max:32'],
            'billing.city' => ['required', 'string', 'max:255'],
            'billing.address' => ['required', 'string', 'max:255'],

            'shipping_same_as_billing' => ['nullable', 'boolean'],
            'shipping.name' => ['required_without:shipping_same_as_billing', 'string', 'max:255'],
            'shipping.country_id' => ['required_without:shipping_same_as_billing', 'integer', 'exists:countries,id'],
            'shipping.zip_code' => ['required_without:shipping_same_as_billing', 'string', 'max:32'],
            'shipping.city' => ['required_without:shipping_same_as_billing', 'string', 'max:255'],
            'shipping.address' => ['required_without:shipping_same_as_billing', 'string', 'max:255'],

            'order_payment_id' => ['required', 'integer', 'exists:order_payments,id'],
            'order_shipping_id' => ['required', 'integer', 'exists:order_shippings,id'],

            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
