<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class GetVendorOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|string|in:pending,preparing,picked_up,delivered',
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:5|max:50',
        ];
    }
}
