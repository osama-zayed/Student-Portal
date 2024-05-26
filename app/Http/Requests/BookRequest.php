<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:2048',
            'url' => 'nullable|file|mimes:pdf,pptx,ppt|max:12800', 
            'description' => 'required|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
           'name.required'=>"عنوان الكتاب مطلوب",
           'name.string'=>"عنوان الكتاب يجب ان يكون نص",
           'name.max'=>"اكبر حد لعنوان الكتاب 255 حرف",
           'description.required'=>"وصف الكتاب مطلوب",
           'description.string'=>"وصف الكتاب يجب ان يكون نص",
           'description.max'=>"اكبر حد لوصف الكتاب 1000 حرف",
           'image.image'=>"صورة الكتاب يجب ان تكون من نوع صورة",
           'image.mimes'=>"صورة الكتاب يجب ان تكون من نوع png,jpg,jpeg,gif,bmp",
           'image.max'=>"اكبر حد لصورة الكتاب 2048 ",
           'url.file'=>"الكتاب يجب ان يكون من نوع pdf,pptx,ppt",
           'url.mimes'=>"الكتاب يجب ان يكون من نوع pdf,pptx,ppt",
           'url.max'=>"اكبر حد لصورة الكتاب 12800 ",
        ];
    }
}
