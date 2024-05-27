<?php

namespace App\Http\Requests\SemesterTask;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddSemesterTaskRequest extends FormRequest
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
            'student_id' => 'required|integer|min:1',
            'course_id' => 'required|integer|min:1',
            'specialization_id' => 'required|integer|min:1',
            'semester_num' => 'required|integer|min:1',
            'academic_work_grade' => 'required|numeric|between:0,100',
            'attendance' => 'required|numeric|between:0,100',
            'midterm_grade' => 'required|numeric|between:0,100',
            'final_grade' => 'required|numeric|lte:60',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'حقل معرف الطالب مطلوب',
            'student_id.integer' => 'حقل معرف الطالب يجب أن يكون عددًا صحيحًا',
            'student_id.min' => 'حقل معرف الطالب يجب أن يكون أكبر من أو يساوي 1',
            'course_id.required' => 'حقل معرف المقرر مطلوب',
            'course_id.integer' => 'حقل معرف المقرر يجب أن يكون عددًا صحيحًا',
            'course_id.min' => 'حقل معرف المقرر يجب أن يكون أكبر من أو يساوي 1',
            'specialization_id.required' => 'حقل معرف التخصص مطلوب',
            'specialization_id.integer' => 'حقل معرف التخصص يجب أن يكون عددًا صحيحًا',
            'specialization_id.min' => 'حقل معرف التخصص يجب أن يكون أكبر من أو يساوي 1',
            'semester_num.required' => 'حقل معرف الترم الدراسي مطلوب',
            'semester_num.integer' => 'حقل معرف الترم الدراسي يجب أن يكون عددًا صحيحًا',
            'semester_num.min' => 'حقل معرف الترم الدراسي يجب أن يكون أكبر من أو يساوي 1',
            'academic_work_grade.required' => 'درجة الاعمال الدراسية مطلوبة',
            'academic_work_grade.numeric' => 'درجة الاعمال الدراسية مطلوبة',
            'academic_work_grade.between' => 'درجة الاعمال الدراسية يجب ان تكون بين0-20',
            'attendance.required' => 'درجة الحضور والغياب مطلوبة',
            'attendance.numeric' => 'درجة الحضور والغياب يجب ان تكون رقماً',
            'attendance.between' => 'درجة الحضور والغياب يجب ان تكون بين0-20',
            'midterm_grade.required' => 'درجة الاختبار النصفي مطلوبة',
            'midterm_grade.numeric' => 'درجة الاختبار يجب ان تكون رقماً',
            'midterm_grade.between' => 'درجة الاختبار النصفي يجب ان تكون بين0-20',
            'final_grade.required' => 'المجموع النهائي مطلوب',
            'final_grade.numeric' => 'يجب ان يكون المجموع النهائي رقم',
            'final_grade.lte' => 'يجب ان يكون المجموع النهائي اقل من 60',
        ];
    }
}
