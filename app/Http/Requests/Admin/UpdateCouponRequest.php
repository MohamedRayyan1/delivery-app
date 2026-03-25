<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($this->route('id')),
            ],
            'discount_type' => 'required|string|in:percent,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_price' => 'nullable|numeric|min:0',
            'expiry_date' => 'required|date|after:now',
            'usage_limit' => 'nullable|integer|min:1',
        ];
    }
}
