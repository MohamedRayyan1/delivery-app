<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                 => 'sometimes|string|max:255',
            'city'                 => 'sometimes|string|max:100',
            'vehicle_type'         => 'sometimes|in:motorcycle,car',
            'vehicle_plate_number' => 'sometimes|nullable|string|max:20',
            'license_image'        => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }
}
