<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddTeacherRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    protected function failedValidation(Validator $validator)
    {
        $errorMessages = [];
        foreach ($validator->errors()->all() as $error) {
            $errorMessages[] = $error;
        }
        $mergedMessage = implode(" و ", $errorMessages);
        toastr()->error($mergedMessage);
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|regex:/^([\p{Arabic}]+\s){3}[\p{Arabic}]+$/u',
            'qualification' => 'required|string',
            'gender' => 'required|string|min:2',
            'phone_number' => 'required|numeric|digits:9|starts_with:7',
            'address' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل اسم المدرس مطلوب',
            'name.string' => 'حقل اسم المدرس يجب أن يكون نصًا',
            'name.regex' => 'حقل اسم المدرس يجب أن يكون رباعي الأسماء',
            'gender.required' => 'حقل الجنس مطلوب',
            'gender.string' => 'حقل الجنس يجب أن يكون نصًا',
            'gender.min' => 'حقل الجنس يجب أن يتكون من الحد الأدنى للحروف',
            'phone_number.required' => 'حقل رقم الهاتف مطلوب',
            'phone_number.numeric' => 'حقل رقم الهاتف يجب أن يكون عددًا صحيحًا',
            'phone_number.digits' => 'حقل رقم الهاتف يجب أن يتكون من 9 أرقام',
            'phone_number.starts_with' => 'حقل رقم الهاتف يجب أن يبدأ برقم 7',
            'qualification.required' => 'حقل المؤهل التعليمي مطلوب',
            'qualification.string' => 'حقل المؤهل التعليمي يجب أن يكون نصًا',
            'qualification.in' => 'حقل المؤهل التعليمي يجب أن يكون إما بكالوريوس أو ماجستير أو دكتوراه',
            'address.required' => 'حقل عنوان المدرس مطلوب',
            'address.string' => 'حقل عنوان المدرس يجب أن يكون نصًا',
        ];
    }
}
