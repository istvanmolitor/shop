<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'invoice_same_as_shipping' => ['nullable', 'boolean'],
            'name' => ['string', 'max:255'],
            'country_id' => ['integer', 'exists:countries,id'],
            'zip_code' => ['string', 'max:32'],
            'city' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
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

