<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemExtraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menu_item_id' => 'required|exists:menu_items,id',
            'name'         => 'required|string|max:255',
            'category'     => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
        ];
    }
}
