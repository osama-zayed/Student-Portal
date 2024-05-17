<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourseRequest extends FormRequest
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
            'name' => 'required|string|min:2',
            'hours' => 'required|integer',
            'specialization_id' => 'required|integer',
            'semester_num' => 'required|integer',
            'teachers_id' => 'nullable|integer',
        ];

    }

    public function messages(): array
    {
        return [
            'name.required' => "اسم المقرر مطلوب",
            'name.string' => "يجب ان يكون اسم المقرر نص",
            'name.max' => "اكبر حد لاسم المقرر 255 حرف",
            'name.min' => "اقل حد لاسم المقرر 2",
            'name.unique' => "يجب ان يكون اسم المقرر فريد",
            'hours.required' => "عدد ساعات المقرر مطلوبة",
            'hours.integer' => "عدد ساعات المقرر يجب ان يكون رقم",
            'specialization_id.required' => "رقم المقرر مطلوب",
            'specialization_id.integer' => "رقم المقرر يجب ان يكون رقم",
            'semester_num.required' => "الترم الدراسي مطلوب",
            'semester_num.integer' => "الترم الدراسي يجب ان يكون رقم",
            'teachers_id.integer' => "رقم المدرس يجب ان يكون رقم",
        ];
    }
}
