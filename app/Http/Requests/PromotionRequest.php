<?php

namespace App\Http\Requests;

use App\Models\SchoolYear;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PromotionRequest extends FormRequest
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
        $from_specialization = Specialization::find($this->from_specialization_id);
        $to_specialization = Specialization::find($this->to_specialization_id);
        $school_year = SchoolYear::find($this->academic_year);
        $school_year_new = SchoolYear::find($this->academic_year_new);
        $to_semester_num = $this->to_semester_num;
        $start_date_diff = Carbon::parse($school_year_new->start_date)->diffInMonths(Carbon::parse($school_year->start_date));

        return [
            'from_semester_num' => ['required', 'integer', 'max:' . $from_specialization->Number_of_semester_of_study],
            'to_semester_num' => ['required', 'integer', 'min:' . ($this->from_semester_num + 1), 'max:' . min($to_specialization->Number_of_semester_of_study, $this->from_semester_num + 1)],
            'from_specialization_id' => 'required|integer',
            'to_specialization_id' => 'required|integer',
            'academic_year' => ['required', 'integer', function ($attribute, $value, $fail) use ($to_semester_num, $start_date_diff) {
                $currentDate = now()->format('Y-m-d');
                $schoolYearStartDate = SchoolYear::find($value)->start_date;
                if (Carbon::parse($schoolYearStartDate)->lessThanOrEqualTo(Carbon::parse($currentDate))) {
                    if ($to_semester_num % 2 != 0) {
                        if ($start_date_diff < 8) {
                            $fail('يجب أن يكون الفرق بين العام الدراسي الجديد والقديم 8 أشهر على الأقل.');
                        }
                    } else {
                        if ($start_date_diff != 0) {
                            $fail('يجب أن يكون العام الدراسي هو نفسه في البيانات الجديدة والقديمة.');
                        }
                    }
                } else {
                    $fail('يجب أن يكون العام الدراسي أقل من تاريخ اليوم الحالي.');
                }
            }],
            'academic_year_new' => 'required|integer',
        ];
    }

    public function messages(): array
    {

        return [
            'from_semester_num.required' => "الترم الدراسي السابق مطلوب",
            'from_semester_num.integer' => "الترم الدراسي السابق يجب ان يكون رقم",
            'from_semester_num.max' => "الترم الدراسي القديم اكبر من عدد الاترام في التخصص",
            'to_semester_num.required' => "الترم الدراسي الجديد مطلوب",
            'to_semester_num.integer' => "الترم الدراسي الجديد يجب ان يكون رقم",
            'to_semester_num.max' => "الترم الدراسي الجديد اكبر من عدد الاترام في التخصص",
            'to_semester_num.min' => "الترم الدراسي الجديد اكبر من اللازم",
            'from_specialization_id.required' => "التخصص القديم مطلوب",
            'from_specialization_id.integer' => "التخصص القديم يجب ان يكون رقم",
            'to_specialization_id.required' => "التخصص الجديد مطلوب",
            'to_specialization_id.integer' => "التخصص الجديد يجب ان يكون رقم",
            'academic_year.required' => "العام الدراسي السابق مطلوب",
            'academic_year.integer' => "العام الدراسي السابق يجب ان يكون رقم",
            'academic_year_new.required' => "العام الدراسي الجديد مطلوب",
            'academic_year_new.integer' => "العام الدراسي الجديد يجب ان يكون رقم",
        ];
    }
}
