<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $orderShippingId = (int) data_get(session('checkout', []), 'order_shipping_id');
        return [
            // Billing data provided on payment step; can be same as shipping
            'billing_same_as_shipping' => ['nullable', 'boolean'],
            'billing.name' => ['required_without:billing_same_as_shipping', 'string', 'max:255'],
            'billing.country_id' => ['required_without:billing_same_as_shipping', 'integer', 'exists:countries,id'],
            'billing.zip_code' => ['required_without:billing_same_as_shipping', 'string', 'max:32'],
            'billing.city' => ['required_without:billing_same_as_shipping', 'string', 'max:255'],
            'billing.address' => ['required_without:billing_same_as_shipping', 'string', 'max:255'],

            // Payment must be allowed for selected shipping
            'order_payment_id' => [
                'required',
                'integer',
                Rule::exists('order_shipping_payments', 'order_payment_id')
                    ->when($orderShippingId > 0, function ($rule) use ($orderShippingId) {
                        return $rule->where('order_shipping_id', $orderShippingId);
                    }),
            ],
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
