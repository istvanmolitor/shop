<?php

namespace Molitor\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Molitor\Order\Models\OrderShipping;
use Molitor\Order\Services\ShippingHandler;

class ShippingStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'order_payment_id' => ['required', 'integer', 'exists:order_payments,id'],
            'order_shipping_id' => ['required', 'integer', 'exists:order_shippings,id'],
            'shipping_data' => ['nullable', 'array'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $shippingId = $this->input('order_shipping_id');
            $shippingData = $this->input('shipping_data', []);

            if ($shippingId) {
                /** @var OrderShipping $shipping */
                $shipping = OrderShipping::find($shippingId);
                if ($shipping && $shipping->type) {
                    /** @var ShippingHandler $handler */
                    $handler = app(ShippingHandler::class);
                    $shippingType = $handler->getShippingType($shipping->type);

                    if ($shippingType) {
                        try {
                            $shippingType->validate($shippingData);
                        } catch (\Illuminate\Validation\ValidationException $e) {
                            foreach ($e->errors() as $key => $messages) {
                                foreach ($messages as $message) {
                                    $validator->errors()->add('shipping_data.' . $key, $message);
                                }
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return __('shop::validation.attributes');
    }
}
