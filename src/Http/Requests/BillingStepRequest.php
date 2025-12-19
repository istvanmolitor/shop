<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'billing_same_as_shipping' => ['nullable', 'boolean'],
            'billing.name' => ['required_without:billing_same_as_shipping', 'string', 'max:255'],
            'billing.country_id' => ['required_without:billing_same_as_shipping', 'integer', 'exists:countries,id'],
            'billing.zip_code' => ['required_without:billing_same_as_shipping', 'string', 'max:32'],
            'billing.city' => ['required_without:billing_same_as_shipping', 'string', 'max:255'],
            'billing.address' => ['required_without:billing_same_as_shipping', 'string', 'max:255'],
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

