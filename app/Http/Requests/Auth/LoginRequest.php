<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'password' => 'required|string',
            'fcm_token' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ];
    }

}
