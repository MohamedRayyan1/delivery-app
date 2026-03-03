<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
return [
            'manager_user_id' => 'sometimes|required|integer|exists:users,id',
            'name' => 'sometimes|required|string|max:255',
            'governorate' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'delivery_cost' => 'nullable|numeric',
            'min_order_price' => 'nullable|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ];
    }

}
