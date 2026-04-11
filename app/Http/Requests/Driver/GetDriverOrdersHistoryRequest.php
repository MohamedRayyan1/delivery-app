<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class GetDriverOrdersHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|string|in:delivered,accepted,picked_up,pending', // يمكن توسعتها حسب الحالات
            'search' => 'nullable|string', // للبحث برقم الطلب
            'per_page' => 'nullable|integer|min:5|max:50',
        ];
    }
}
