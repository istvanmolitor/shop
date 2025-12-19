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
