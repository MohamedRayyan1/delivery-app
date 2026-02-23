<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'password' => 'required|string|min:6', // لازم يبعت حقل password_confirmation
            'city' => 'required|string',
            'fcm_token' => 'nullable|string',
        ];
    }

    // هون السحر: تخصيص الرسائل
    public function messages(): array
    {
        return [
            // رسائل الاسم
            'name.required' => 'يرجى إدخال الاسم الكامل.',
            'name.string' => 'الاسم يجب أن يكون نصاً.',

            // رسائل الهاتف
            'phone.required' => 'رقم الهاتف مطلوب للتسجيل.',
            'phone.unique' => 'رقم الهاتف هذا مستخدم مسبقاً، يرجى تسجيل الدخول.',

            // رسائل كلمة المرور
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون 6 خانات على الأقل.',
        ];
    }

}
