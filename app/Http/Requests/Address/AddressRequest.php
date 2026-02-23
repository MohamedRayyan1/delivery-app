<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => 'nullable|string|max:255',
            'street' => 'required|string|max:255',
            'details' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:13',
            'phone' => 'nullable|string|min:10',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'is_default' => 'boolean',
        ];
    }

    public function messages(): array
{
    return [

        'street.required' => 'يرجى إدخال اسم الشارع.',
        'street.max' => 'اسم الشارع طويل جداً.',

        'lat.required' => 'يجب تحديد الموقع على الخريطة (خط العرض).',
        'lat.numeric' => 'إحداثيات الموقع يجب أن تكون أرقاماً صحيحة.',
        'lng.required' => 'يجب تحديد الموقع على الخريطة (خط الطول).',
        'lng.numeric' => 'إحداثيات الموقع يجب أن تكون أرقاماً صحيحة.',

        'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 13 رقماً.',
        'phone.min' => 'رقم الهاتف يجب أن يكون على الأقل 10 أرقام.',
        'is_default.boolean' => 'قيمة حقل "العنوان الافتراضي" يجب أن تكون صحيح أو خطأ.',
        'string' => 'يجب أن تكون القيمة المدخلة نصاً.',
    ];
}

}
