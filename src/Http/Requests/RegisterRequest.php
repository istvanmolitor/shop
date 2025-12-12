<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Customer optional fields
            'customer_name' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],

            // Addresses fields
            'invoice_name' => ['required', 'string', 'max:255'],
            'invoice_country_id' => ['required', 'integer', 'exists:countries,id'],
            'invoice_zip_code' => ['required', 'string', 'max:32'],
            'invoice_city' => ['required', 'string', 'max:255'],
            'invoice_address' => ['required', 'string', 'max:255'],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_country_id' => ['required', 'integer', 'exists:countries,id'],
            'shipping_zip_code' => ['required', 'string', 'max:32'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:255'],
        ];
    }
}
