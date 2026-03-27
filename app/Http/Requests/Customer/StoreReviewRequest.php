<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'restaurant_rating' => 'nullable|numeric|min:1|max:5',
            'driver_rating' => 'nullable|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
