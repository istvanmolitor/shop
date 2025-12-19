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
            'invoice.name' => ['required_without:invoice_same_as_shipping', 'string', 'max:255'],
            'invoice.country_id' => ['required_without:invoice_same_as_shipping', 'integer', 'exists:countries,id'],
            'invoice.zip_code' => ['required_without:invoice_same_as_shipping', 'string', 'max:32'],
            'invoice.city' => ['required_without:invoice_same_as_shipping', 'string', 'max:255'],
            'invoice.address' => ['required_without:invoice_same_as_shipping', 'string', 'max:255'],
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

