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
'lat' => ['required', 'numeric', 'between:-90,90'], // غيّر float لـ numeric
'lng' => ['required', 'numeric', 'between:-180,180'], // غيّر float لـ numeric
            'description' => 'nullable|string',
            'menu_section_ids' => 'required|array|min:1',
            'delivery_cost' => 'nullable|numeric',
            'min_order_price' => 'nullable|numeric',
            'delivery_time' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ];
    }


}
