<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:4048',
            'description' => 'required|string|max:1500'
        ];
    }

    public function messages(): array
    {
        return [
           'title.required'=>"عنوان الخبر مطلوب",
           'title.string'=>"عنوان الخبر يجب ان يكون نص",
           'title.max'=>"اكبر حد لعنوان الخبر 255 حرف",
           'description.required'=>"وصف الخبر مطلوب",
           'description.string'=>"وصف الخبر يجب ان يكون نص",
           'description.max'=>"اكبر حد لوصف الخبر 1500 حرف",
           'image.image'=>"صورة الخبر يجب ان تكون من نوع صورة",
           'image.mimes'=>"صورة الخبر يجب ان تكون من نوع png,jpg,jpeg,gif,bmp",
           'image.max'=>"اكبر حد لصورة الخبر 4048 ",
        ];
    }
}
