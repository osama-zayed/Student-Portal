<?php

namespace App\Http\Controllers\api;

use App\Models\SemesterTask;
use App\Http\Controllers\Controller;
use App\Models\Result;
use Exception;

class gradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('userStatus');
    }
    public function semesterTask()
    {
        try {
            $data = request()->validate([
                'semester_num' => 'required|integer',
            ], [
                'semester_num.required' => 'رقم الترم الدراسي مطلوب',
                'semester_num.integer' => 'يجب ان يكون الترم رقماً',
            ]);
            $SemesterTask = SemesterTask::select(
                'id',
                'course_id',
                'semester_num',
                'academic_work_grade',
                'attendance',
                'midterm_grade',
                'practicality_grade',
                'final_grade',
            )
                ->where('specialization_id', auth('api')->user()->specialization_id)
                ->where('student_id', auth('api')->user()->id)
                ->where('semester_num', $data['semester_num'])
                ->with('course')
                ->get();
            $transformedData = $SemesterTask->map(function ($SemesterTask, $num) {
                return [
                    'id' => ++$num,
                    'course_name' => $SemesterTask->course->name,
                    'semester_num' => $SemesterTask->semester_num,
                    'academic_work_grade' => $SemesterTask->academic_work_grade,
                    'attendance' => $SemesterTask->attendance,
                    'midterm_grade' => $SemesterTask->midterm_grade,
                    'practicality_grade' => $SemesterTask->practicality_grade,
                    'final_grade' => $SemesterTask->final_grade,
                ];
            });
            if ($SemesterTask->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد كتب'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function result()
    {
        try {
            $data = request()->validate([
                'semester_num' => 'required|integer',
            ], [
                'semester_num.required' => 'رقم الترم الدراسي مطلوب',
                'semester_num.integer' => 'يجب ان يكون الترم رقماً',
            ]);
            $SemesterTask = Result::select(
                'id',
                'course_id',
                'semester_num',
                'academic_work_grade',
                'final_exam_grade',
                'final_grade',
                'status',
            )
                ->where('specialization_id', auth('api')->user()->specialization_id)
                ->where('student_id', auth('api')->user()->id)
                ->where('semester_num', $data['semester_num'])
                ->with('course')
                ->get();
            $transformedData = $SemesterTask->map(function ($SemesterTask, $num) {
                return [
                    'id' => ++$num,
                    'course_name' => $SemesterTask->course->name,
                    'semester_num' => $SemesterTask->semester_num,
                    'academic_work_grade' => $SemesterTask->academic_work_grade,
                    'final_exam_grade' => $SemesterTask->final_exam_grade,
                    'final_grade' => $SemesterTask->final_grade,
                    'status' => $SemesterTask->status,
                ];
            });
            if ($SemesterTask->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد كتب'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
}
