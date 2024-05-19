<?php

namespace App\Http\Controllers\api;

use App\Models\Course;
use App\Http\Controllers\Controller;
use Exception;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('userStatus');
    }
    public function showByStudent()
    {
        try {
            $Course = Course::select(
                'id',
                'name',
                'hours',
                'specialization_id',
                'semester_num',
                'teachers_id'
            )->orderBy('semester_num', 'asc')
            ->with("teachers")
                ->where('semester_num', auth('api')->user()->semester_num)
                ->where('specialization_id', auth('api')->user()->specialization_id)
                ->get();
            $transformedData = $Course->map(function ($Course, $num) {
                return [
                    'id' => ++$num,
                    'name' => $Course->name,
                    'hours' => $Course->hours,
                    'teachers_id' => $Course->teachers_id??"",
                    'teachers_name' => $Course->teachers->name??"",
                ];
            });
            if ($Course->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد تخصصات'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function showBySpecialization()
    {
        try {
            $data =  request()->validate([
                'specialization_id' => 'required|integer|min:1',
            ], [
                'specialization_id.required' => 'حقل معرف التخصص مطلوب',
                'specialization_id.integer' => 'حقل معرف التخصص يجب ان يكون رقم',
                'specialization_id.max' => "اكبر حد لمعرف التخصص 255 حرف",
                'specialization_id.min' => "اقل حد لمعرف التخصص 1",
            ]);
            $Course = Course::select(
                'id',
                'name',
                'hours',
                'specialization_id',
                'semester_num',
            )->orderBy('semester_num', 'asc')
                ->where('specialization_id',$data['specialization_id'] )
                ->get();
            $transformedData = $Course->map(function ($Course, $num) {
                return [
                    'id' => ++$num,
                    'name' => $Course->name,
                    'hours' => $Course->hours,
                    'semester_num' => $Course->semester_num,
                ];
            });
            if ($Course->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد تخصصات'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
}
