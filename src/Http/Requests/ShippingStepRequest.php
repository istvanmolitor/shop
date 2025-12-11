<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'shipping.name' => ['required', 'string', 'max:255'],
            'shipping.country_id' => ['required', 'integer', 'exists:countries,id'],
            'shipping.zip_code' => ['required', 'string', 'max:32'],
            'shipping.city' => ['required', 'string', 'max:255'],
            'shipping.address' => ['required', 'string', 'max:255'],
            // Shipping method now selected on step 1
            'order_shipping_id' => ['required', 'integer', 'exists:order_shippings,id'],
        ];
    }
}
