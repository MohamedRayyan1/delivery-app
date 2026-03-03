<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manager_user_id' => 'required|integer|exists:users,id|unique:restaurants,manager_user_id',
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('restaurants')->where(fn ($q) => $q->where('city', $this->city))
            ],
            'governorate' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'logo' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'description' => 'nullable|string',
            'delivery_cost' => 'nullable|numeric',
            'min_order_price' => 'nullable|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ];
    }


}
