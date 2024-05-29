<?php

namespace App\Http\Requests\Student;

use App\Models\College;
use App\Models\Specialization;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Unique;

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
            'personal_id' => [
                'required',
                'numeric',
                'min:1',
                'unique:students,personal_id,' . $this->id . ',id',
            ],
            'full_name' => 'required|string|regex:/^([\p{Arabic}]+\s){3}[\p{Arabic}]+$/u',
            'gender' => 'required|string|min:2',
            'nationality' => 'required|string|min:2',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|min:2',
            'phone_number' => 'required|numeric|digits:9|starts_with:7',
            'relative_phone_number' => 'required|numeric|digits:9|starts_with:7',
            'educational_qualification' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $specialization = Specialization::find(request()->input('specialization_id'));
                    if ($value !== $specialization->educational_qualification) {
                        $fail('المؤهل للتخصص ' . $specialization->name . ' يجب ان يكون ' . $specialization->educational_qualification . '.');
                    }
                },
            ],
            'high_school_grade' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
                function ($attribute, $value, $fail) {
                    $specialization = Specialization::find(request()->input('specialization_id'));
                    if ($value < $specialization->lowest_acceptance_rate) {
                        $fail('اقل معدل مطلوب للتخصص ' . $specialization->name . ' يجب ان يكون ' . $specialization->lowest_acceptance_rate . '.');
                    }
                },
            ],
            'school_graduation_date' => 'required|date|before:today',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4098',
            'academic_year' => 'required|integer',
            'college_id' => 'required|integer|min:1',
            'specialization_id' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $college = College::find(request()->input('college_id'));
                    if (!$college->Specialization()->whereId($value)->exists()) {
                        $fail('التخصص ليس ضمن كلية ' . $college->name);
                    }
                },
            ],
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
            'phone_number.required' => 'حقل رقم الهاتف مطلوب',
            'phone_number.numeric' => 'حقل رقم الهاتف يجب أن يكون عددًا صحيحًا',
            'phone_number.digits' => 'حقل رقم الهاتف يجب أن يتكون من 9 أرقام',
            'phone_number.starts_with' => 'حقل رقم الهاتف يجب أن يبدأ برقم 7',
            'relative_phone_number.required' => 'حقل رقم هاتف القريب مطلوب',
            'relative_phone_number.numeric' => 'حقل رقم هاتف القريب يجب أن يكون عددًا صحيحًا',
            'relative_phone_number.digits' => 'حقل رقم هاتف القريب يجب أن يتكون من 9 أرقام',
            'relative_phone_number.starts_with' => 'حقل رقم هاتف القريب يجب أن يبدأ برقم 7',
            'educational_qualification.required' => 'حقل المؤهل التعليمي مطلوب',
            'educational_qualification.string' => 'حقل المؤهل التعليمي يجب أن يكون نصًا',
            'educational_qualification.in' => 'حقل المؤهل التعليمي يجب أن يكون إما بكالوريوس أو ماجستير أو دكتوراه',
            'high_school_grade.required' => 'حقل معدل المدرسة الثانوية مطلوب',
            'high_school_grade.numeric' => 'حقل معدل المدرسة الثانوية يجب أن يكون عددًا صحيحًا',
            'high_school_grade.min' => 'حقل معدل المدرسة الثانوية يجب أن يكون على الأقل 0',
            'high_school_grade.max' => 'درجة المدرسة الثانوية يجب أن تكون بحد أقصى 100.',
            'school_graduation_date.required' => 'يجب إدخال تاريخ التخرج من المدرسة الثانوية.',
            'school_graduation_date.date' => 'تاريخ التخرج من المدرسة الثانوية يجب أن يكون تاريخًا صالحًا.',
            'school_graduation_date.before' => 'تاريخ التخرج من المدرسة الثانوية يجب أن يكون قبل التاريخ الحالي.',
            'discount_percentage.required' => 'يجب إدخال نسبة الخصم.',
            'discount_percentage.numeric' => 'نسبة الخصم يجب أن تكون رقمية.',
            'discount_percentage.min' => 'نسبة الخصم يجب أن تكون على الأقل 0%.',
            'discount_percentage.max' => 'نسبة الخصم يجب أن تكون بحد أقصى 100%.',
            'image.nullable' => 'الصورة هي حقل اختياري.',
            'image.image' => 'الملف المرفق يجب أن يكون صورة صالحة.',
            'image.mimes' => 'الصور المسموح بها هي: jpeg, png, jpg, gif.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 4098 كيلوبايت.',
            'academic_year.required' => 'تاريخ العام الدراسي مطلوب',
            'academic_year.integer' => 'تاريخ العام الدراسي يجب ان يكون رقماً',

        ];
    }
}
