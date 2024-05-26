<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SpecializationsRequest extends FormRequest
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
            'name' => 'required|string|unique:specializations,name|min:2',
            'Number_of_years_of_study' => 'required|integer|min:4|max:6',
            'Price' => 'required|integer|min:500|max:6000',
            'college_id' => 'required|integer|min:1',
            'educational_qualification' => 'required|string|min:1',
            'lowest_acceptance_rate' => 'required|integer|min:50|max:90',
        ];
    }

    public function messages(): array
    {
        return [
            'Number_of_years_of_study.required' => "عدد السنين الدراسية مطلوب",
            'Number_of_years_of_study.integer' => "يجب أن يكون عدد السنين الدراسية رقم",
            'Number_of_years_of_study.min' => "يجب أن يكون عدد السنين الدراسية اكبر من 3",
            'Number_of_years_of_study.max' => "يجب أن يكون عدد السنين الدراسية اقل من 6",
            'college_id.required' => "رقم الكلية مطلوب",
            'college_id.integer' => "يجب أن يكون رقم الكلية رقم",
            'college_id.min' => "يجب أن يكون رقم الكلية اكبر من 0",
            'college_id.max' => "يجب أن يكون رقم الكلية اقل من 100",
            'lowest_acceptance_rate.required' => "اقل معدل مطلوب",
            'lowest_acceptance_rate.integer' => "يجب أن يكون رقم معدل رقم",
            'lowest_acceptance_rate.min' => "يجب أن يكون اقل معدل اكبر من 50",
            'lowest_acceptance_rate.max' => "يجب أن يكون اقل معدل اقل من 90",
            'Price.required' => "السعر مطلوب",
            'Price.integer' => "يجب أن يكون السعر رقم",
            'Price.min' => "يجب أن يكون السعر اكبر من 500$",
            'Price.max' => "يجب أن يكون السعر اقل من 6000$",
            'name.required' => 'حقل الاسم مطلوب',
            'name.string' => "يجب ان يكون اسم التخصص نص",
            'name.max' => "اكبر حد لاسم التخصص 255 حرف",
            'name.min' => "اقل حد لاسم التخصص 2",
            'name.unique' => "يجب ان يكون اسم التخصص فريد",
            'educational_qualification.required' => 'حقل المؤهل العلمي مطلوب',
            'educational_qualification.string' => "يجب ان يكون المؤهل العلمي نص",
            'educational_qualification.max' => "اكبر حد لاسم المؤهل العلمي 255 حرف",
            'educational_qualification.min' => "اقل حد لاسم المؤهل العلمي 2",
        ];
    }
}
