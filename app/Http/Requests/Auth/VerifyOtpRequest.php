<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:20',
            'code' => 'required|string|size:5',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'رقم الهاتف مطلوب.',
            'code.required' => 'رمز التحقق مطلوب.',
            'code.size' => 'رمز التحقق يجب أن يتكون من 5 أرقام.',
        ];
    }


}
