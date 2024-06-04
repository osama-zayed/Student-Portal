<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionRequest;
use App\Models\Course;
use App\Models\Promotion;
use App\Models\Result;
use App\Models\SemesterTask;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.Promotion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromotionRequest $request)
    {
        try {
            $user = auth()->user();
            if ($user->user_type == 'student_affairs' || $user->user_type == 'registration') {
                toastr()->error("غير مصرح لك");
                return redirect()->back();
            }
            $Students = Student::select(
                'id',
                'specialization_id',
                'semester_num',
                'academic_year',
                'status'
            )
                ->where('specialization_id', $request['from_specialization_id'])
                ->where('semester_num', $request['from_semester_num'])
                ->where('academic_year', $request['academic_year'])
                ->get();
            $courses = Course::select('id')->where('specialization_id', $request['from_specialization_id'])->where('semester_num', $request['from_semester_num'])->get();
            foreach ($Students as $Student) {
                foreach ($courses as $course) {
                    $this->checkCourseForResultAndSemesterTask($request['from_semester_num'], $course, $Student['id'], $request['from_specialization_id']);
                }
                
                $this->PromotionStudent($request['from_semester_num'], $Student, $request['from_specialization_id'], $request['academic_year_new'], $request['to_semester_num'], $request['to_specialization_id'], $request['academic_year']);
            }

            toastr()->success('تمت العملية بنجاح');
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    public static function PromotionStudent($from_semester_num, $Student, $from_specialization_id, $academic_year_new, $to_semester_num, $to_specialization_id, $academic_year)
    {
        $lowGradeCourses = Result::where('final_grade', '<', 50)
            ->where('semester_num', $from_semester_num)
            ->where('student_id', $Student->id)
            ->count();
        if ($lowGradeCourses > 2) {
            $Student->update([
                'specialization_id' => $from_specialization_id,
                'semester_num' => $from_semester_num,
                'academic_year' => $academic_year_new,
                'status' => 'راسب'
            ]);
            Promotion::create([
                'student_id' => $Student->id,
                'from_semester_num' => $from_semester_num,
                'to_semester_num' => $from_semester_num,
                'from_specialization_id' => $from_specialization_id,
                'to_specialization_id' => $from_specialization_id,
                'academic_year' => $academic_year,
                'academic_year_new' => $academic_year_new,
                'status' => $Student->status,
            ]);
        } else {
            $Student->update([
                'specialization_id' => $to_specialization_id,
                'semester_num' => $to_semester_num,
                'academic_year' => $academic_year_new,
                'status' => 'ناجح'
            ]);
            Promotion::create([
                'student_id' => $Student->id,
                'from_semester_num' => $from_semester_num,
                'to_semester_num' => $to_semester_num,
                'from_specialization_id' => $from_specialization_id,
                'to_specialization_id' => $to_specialization_id,
                'academic_year' => $academic_year,
                'academic_year_new' => $academic_year_new,
                'status' => $Student->status,
            ]);
        }
    }
    public static function checkCourseForResultAndSemesterTask($from_semester_num, $course, $Student_id, $specialization_id)
    {
        $semester_task = SemesterTask::where('course_id', $course->id)
            ->where('semester_num', $from_semester_num)
            ->where('student_id', $Student_id)
            ->first();

        if (!$semester_task) {
            $semester_task = new SemesterTask();
            $semester_task->course_id = $course->id;
            $semester_task->specialization_id = $specialization_id;
            $semester_task->semester_num = $from_semester_num;
            $semester_task->student_id = $Student_id;
            $semester_task->final_grade = 0;
            $semester_task->save();
        }

        $result = Result::where('course_id', $course->id)
            ->where('semester_num', $from_semester_num)
            ->where('student_id', $Student_id)
            ->first();

        if (!$result) {
            $result = new Result();
            $result->course_id = $course->id;
            $result->semester_num = $from_semester_num;
            $result->student_id = $Student_id;
            $result->specialization_id = $specialization_id;
            $result->semester_tasks_id = $semester_task->id;
            $result->final_grade = 0;
            $result->status = 'راسب';
            $result->save();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
