<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'sub_section_id' => 'required|integer|exists:sub_menu_sections,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|lt:price',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
        ];
    }
}
