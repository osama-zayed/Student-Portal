<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InquirieRequest extends FormRequest
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
            'subject' => 'required|string|min:2|max:120',
            'message' => 'required|string|max:1000|min:2',
            'status' => 'nullable|in:pending,resolved,closed',
            'inquirie_type' => 'nullable|in:Darken,inquiry',
            'resolved_at' => 'nullable|date_format:Y-m-d H:i:s',
            'reply_message' => 'nullable|string|max:1000|min:2',
        ];
    }
    
    public function messages(): array
    {
        
        return [
            'subject.required' => "عنوان الشكوى مطلوب",
            'subject.string' => "يجب أن يكون عنوان الشكوى نصًا",
            'subject.max' => "أقصى عدد الأحرف لعنوان الشكوى هو 120",
            'subject.min' => "أقل عدد الأحرف لعنوان الشكوى هو 2",
            'message.required' => "موضوع الشكوى مطلوب",
            'message.string' => "يجب أن يكون موضوع الشكوى نصًا",
            'message.max' => "أقصى عدد الأحرف لموضوع الشكوى هو 1000",
            'message.min' => "أقل عدد الأحرف لموضوع الشكوى هو 2",
            'status.in' => "حالة الشكوى يجب أن تكون إما 'pending' أو 'resolved' أو 'closed'",
            'inquirie_type.in' =>  "نوع الشكوى يجب أن تكون إما 'pending' أو 'resolved' أو 'closed'",
            'resolved_at.date_format' => "تاريخ إنهاء الشكوى يجب أن يكون بتنسيق Y-m-d H:i:s",
            'reply_message.max' => "أقصى عدد الأحرف لرد الشكوى هو 1000",
            'reply_message.min' => "أقل عدد الأحرف لرد الشكوى هو 2",
        ];
    }
}
