<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class DriverRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'city' => 'required|string',
            'fcm_token' => 'nullable|string',

            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8',

            'vehicle_type' => 'required|string|in:motorcycle,car',
            'vehicle_plate_number' => 'nullable|string|max:50',

            'personal_photo' => 'required|image|max:1024',
            'id_card_back' => 'required|image|max:1024',
            'id_card_front' => 'required|image|max:1024',
            'driver_license' => 'required|image|max:1024',
        ];
    }
}
