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
            'invoice.name' => ['required', 'string', 'max:255'],
            'invoice.country_id' => ['required', 'integer', 'exists:countries,id'],
            'invoice.zip_code' => ['required', 'string', 'max:32'],
            'invoice.city' => ['required', 'string', 'max:255'],
            'invoice.address' => ['required', 'string', 'max:255'],

            'shipping_same_as_invoice' => ['nullable', 'boolean'],
            'shipping.name' => ['required_without:shipping_same_as_invoice', 'string', 'max:255'],
            'shipping.country_id' => ['required_without:shipping_same_as_invoice', 'integer', 'exists:countries,id'],
            'shipping.zip_code' => ['required_without:shipping_same_as_invoice', 'string', 'max:32'],
            'shipping.city' => ['required_without:shipping_same_as_invoice', 'string', 'max:255'],
            'shipping.address' => ['required_without:shipping_same_as_invoice', 'string', 'max:255'],

            'order_payment_id' => ['required', 'integer', 'exists:order_payments,id'],
            'order_shipping_id' => ['required', 'integer', 'exists:order_shippings,id'],

            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return __('shop::validation.attributes');
    }
}
