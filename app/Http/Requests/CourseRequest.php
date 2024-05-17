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
            'name' => 'required|string|unique:Courses,name|min:2',
            'hours' => 'required|integer',
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
        ];
    }
}
