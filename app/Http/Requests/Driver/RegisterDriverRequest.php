<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'phone'                => 'required|string|unique:users,phone',
            'city'                 => 'required|string|max:100',
            'vehicle_type'         => 'required|in:motorcycle,car',
            'vehicle_plate_number' => 'nullable|string|max:20',
            'license_image'        => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
        ];
    }
}
