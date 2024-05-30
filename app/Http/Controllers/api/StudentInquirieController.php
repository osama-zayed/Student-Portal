<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\studentInquirie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentInquirieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('userStatus');
    }
    public function showAllComplaint()
    {
        try {
            $studentInquirie = studentInquirie::select(
                'id',
                'subject',
                'status',
            )
                ->where('inquirie_type', 'complaint')
                ->orderBy('id', 'desc')
                ->where('student_id', auth('api')->user()->id)
                ->take(50)
                ->get();
            if ($studentInquirie->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد شكاوي'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $studentInquirie]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function showAllInquiry()
    {
        try {
            $studentInquirie = studentInquirie::select(
                'id',
                'subject',
                'status',
            )
                ->where('inquirie_type', 'inquiry')
                ->orderBy('id', 'desc')
                ->where('student_id', auth('api')->user()->id)
                ->take(50)
                ->get();
            if ($studentInquirie->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد استفسارات'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $studentInquirie]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function Add(Request $request)
    {
        try {
            $credentials = request()->validate([
                'subject' => 'required|string|min:2|max:120',
                'message' => 'required|string|max:1000|min:2',
                'inquirie_type' => 'required|in:complaint,inquiry',
            ], [
                'subject.required' => "عنوان الشكوى مطلوب",
                'subject.string' => "يجب أن يكون عنوان الشكوى نصًا",
                'subject.max' => "أقصى عدد الأحرف لعنوان الشكوى هو 120",
                'subject.min' => "أقل عدد الأحرف لعنوان الشكوى هو 2",
                'message.required' => "موضوع الشكوى مطلوب",
                'message.string' => "يجب أن يكون موضوع الشكوى نصًا",
                'message.max' => "أقصى عدد الأحرف لموضوع الشكوى هو 1000",
                'message.min' => "أقل عدد الأحرف لموضوع الشكوى هو 2",
                'inquirie_type.required' =>  "نوع الشكوى مطلوبة ",
                'inquirie_type.in' =>  "نوع الشكوى يجب أن تكون إما inquiry أو complaint",
            ]);
            studentInquirie::create([
                'student_id' => auth('api')->user()->id,
                'subject' => $credentials['subject'],
                'message' => $credentials['message'],
                'inquirie_type' => $credentials['inquirie_type'],
                'status' => 'لم يتم الرد بعد'
            ]);
            return response()->json(['Status' => true, 'Message' => 'تمت العملية بنجاح']);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $errorMessages[] = $error[0];
            }
            $mergedMessage = implode(" و ", $errorMessages);

            return response()->json(['Status' => false, 'Message' => $mergedMessage], 400);
        } catch (Exception $e) {
            return response()->json(['Status' => false, "Message" => 'العملية فشلت'], 404);
        }
    }
    public function show(Request $request)
    {
        try {
            $data =  $request->validate([
                'id' => 'required|integer|min:1',
            ], [
                'id.required' => 'حقل معرف الشكوى مطلوب',
                'id.integer' => 'حقل معرف الشكوى يجب ان يكون رقم',
                'id.min' => "اقل حد لمعرف الشكوى 1",
            ]);
            $studentInquirie = studentInquirie::select(
                'id',
                'student_id',
                'subject',
                'message',
                'status',
                'inquirie_type',
                'resolved_at',
                'reply_message',
            )
                ->where('id', $data['id'])
                ->orderBy('id', 'desc')
                ->where('student_id', auth('api')->user()->id)
                ->first();
            if (empty($studentInquirie)) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد شكاوي'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $studentInquirie]);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $errorMessages[] = $error[0];
            }
            $mergedMessage = implode(" و ", $errorMessages);

            return response()->json(['Status' => false, 'Message' => $mergedMessage], 400);
        } catch (Exception $e) {
            return response()->json(['Status' => false, "Message" => 'العملية فشلت'], 404);
        }
    }

    public function edit(studentInquirie $studentInquirie)
    {
        //
    }

    public function update(Request $request, studentInquirie $studentInquirie)
    {
        //
    }

    public function destroy(studentInquirie $studentInquirie)
    {
        //
    }
}
