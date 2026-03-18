<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|string|in:cash_on_delivery,credit_card',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ];
    }


}
