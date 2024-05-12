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
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'member' => ['boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'username.required' => 'اسم المستخدم مطلوب',
            'username.string' => 'يجب أن يكون الاسم نصًا',
            'username.max' => 'لقد تجاوز الاسم الحد المطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.string' => 'يجب أن تكون كلمة المرور نصًا',
            'password.min' => 'يجب أن تحتوي كلمة المرور على الأقل :min أحرف',
            'password.max' => 'الحد الأقصى لطول كلمة المرور هو :max حرف',
        ];
    }
}
