<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'item_id'       => 'required|exists:menu_items,id',
            'quantity'      => 'required|integer|min:1',
            'notes'         => 'nullable|string|max:255',
            'extras_ids'    => 'nullable|array',
            'extras_ids.*'  => 'integer|exists:item_extras,id',
        ];
    }
}
