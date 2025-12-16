<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users can update their profile
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'customer_name' => ['required', 'string', 'max:255'],
            'invoice.name' => ['required', 'string', 'max:255'],
            'invoice.country_id' => ['required', 'integer', 'exists:countries,id'],
            'invoice.zip_code' => ['required', 'string', 'max:32'],
            'invoice.city' => ['required', 'string', 'max:255'],
            'invoice.address' => ['required', 'string', 'max:255'],
            'shipping.name' => ['required', 'string', 'max:255'],
            'shipping.country_id' => ['required', 'integer', 'exists:countries,id'],
            'shipping.zip_code' => ['required', 'string', 'max:32'],
            'shipping.city' => ['required', 'string', 'max:255'],
            'shipping.address' => ['required', 'string', 'max:255'],
        ];
    }
}
