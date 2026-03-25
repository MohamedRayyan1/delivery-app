<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|string|in:percent,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_order_price' => 'nullable|numeric|min:0',
            'expiry_date' => 'required|date|after:now',
            'usage_limit' => 'nullable|integer|min:1',
        ];
    }
}
