<?php

namespace App\Http\Requests\Student;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddStudentRequest extends FormRequest
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
            'personal_id' => 'required|numeric|min:1|unique:students,personal_id',
            'full_name' => 'required|string|regex:/^([\p{Arabic}]+\s){3}[\p{Arabic}]+$/u',
            'gender' => 'required|string|min:2',
            'nationality' => 'required|string|min:2',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|min:2',
            'phone_number' => '',
            'relative_phone_number' => '',
            'educational_qualification' => '',
            'high_school_grade' => '',
            'school_graduation_date' => '',
            'discount_percentage' => '',
            'college_id' => 'required|integer|min:1',
            'specialization_id' => 'required|integer|min:1',
            'image' => '',
        ];
    }

    public function messages(): array
    {
        return [
            'personal_id.required' => 'حقل رقم البطاقة الشخصية مطلوب',
            'personal_id.numeric' => 'حقل رقم البطاقة الشخصية يجب أن يكون عددًا صحيحًا',
            'personal_id.min' => 'حقل رقم البطاقة الشخصية يجب أن يكون أكبر من أو يساوي 1',
            'personal_id.unique' => 'رقم البطاقة الشخصية موجود بالفعل في قاعدة البيانات',
            'full_name.required' => 'حقل اسم الطالب مطلوب',
            'full_name.string' => 'حقل اسم الطالب يجب أن يكون نصًا',
            'full_name.regex' => 'حقل اسم الطالب يجب أن يكون رباعي الأسماء',
            'gender.required' => 'حقل الجنس مطلوب',
            'gender.string' => 'حقل الجنس يجب أن يكون نصًا',
            'gender.min' => 'حقل الجنس يجب أن يتكون من الحد الأدنى للحروف',
            'nationality.required' => 'حقل الجنسية مطلوب',
            'nationality.string' => 'حقل الجنسية يجب أن يكون نصًا',
            'nationality.min' => 'حقل الجنسية يجب أن يتكون من الحد الأدنى للحروف',
            'date_of_birth.required' => 'حقل تاريخ الميلاد مطلوب',
            'date_of_birth.date' => 'حقل تاريخ الميلاد يجب أن يكون تاريخًا صحيحًا',
            'place_of_birth.required' => 'حقل محل الميلاد مطلوب',
            'place_of_birth.string' => 'حقل محل الميلاد يجب أن يكون نصًا',
            'place_of_birth.min' => 'حقل محل الميلاد يجب أن يتكون من الحد الأدنى للحروف',
            'college_id.required' => 'حقل معرف الكلية مطلوب',
            'college_id.integer' => 'حقل معرف الكلية يجب أن يكون عددًا صحيحًا',
            'college_id.min' => 'حقل معرف الكلية يجب أن يكون أكبر من أو يساوي 1',
            'specialization_id.required' => 'حقل معرف التخصص مطلوب',
            'specialization_id.integer' => 'حقل معرف التخصص يجب أن يكون عددًا صحيحًا',
            'specialization_id.min' => 'حقل معرف التخصص يجب أن يكون أكبر من أو يساوي 1',
        ];
    }
}
