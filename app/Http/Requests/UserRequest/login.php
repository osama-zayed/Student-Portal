<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class login extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'member' => ['boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.string' => 'يجب أن يكون البريد الإلكتروني نصًا',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا',
            'email.max' => 'الحد الأقصى لطول البريد الإلكتروني هو :max حرف',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.string' => 'يجب أن تكون كلمة المرور نصًا',
            'password.min' => 'يجب أن تحتوي كلمة المرور على الأقل :min أحرف',
            'password.max' => 'الحد الأقصى لطول كلمة المرور هو :max حرف',
        ];
    }
}
