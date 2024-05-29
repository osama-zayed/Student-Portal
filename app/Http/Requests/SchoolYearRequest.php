<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SchoolYearRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'start_date' => 'required|date_format:Y-m|unique:school_years,start_date',
            'end_date' => 'required|date_format:Y-m|after_or_equal:start_date,8 months|unique:school_years,end_date',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => "اسم العام الدراسي مطلوب",
            'name.string' => "اسم العام الدراسي يجب أن يكون نص",
            'name.max' => "أكبر حد لاسم العام الدراسي 255 حرف",
            'start_date.required' => "تاريخ البداية مطلوب",
            'start_date.date_format' => "تاريخ البداية يجب أن يكون في تنسيق (شهر-سنة)",
            'start_date.unique' => "تاريخ البداية المدخل مسجل مسبقا",
            'end_date.required' => "تاريخ النهاية مطلوب",
            'end_date.date_format' => "تاريخ النهاية يجب أن يكون في تنسيق (شهر-سنة)",
            'end_date.after_or_equal' => "يجب أن يكون الفرق بين تاريخ البداية والنهاية 8 أشهر على الأقل",
            'end_date.unique' => "تاريخ النهاية المدخل مسجل مسبقا",
        ];
    }
}
