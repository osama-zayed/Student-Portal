<?php

namespace App\Http\Controllers;

use App\Http\Requests\Result\AddResultRequest;
use Illuminate\Http\Request;
use App\Models\Result;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SemesterTask;
use App\Models\Student;
use Exception;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $collegeId = request()->get('college_id', 0);
            $specializationId = request()->get('specialization_id', 0);
            $semesterNum = request()->get('semester_num', 0);
            $courseId = request()->get('course_id', 0);

            $query = Result::query()->with('course', 'student', 'specialization');

            if ($collegeId) {
                $query->whereHas('student', function ($query) use ($collegeId) {
                    $query->where('college_id', $collegeId);
                });
            }

            if ($specializationId) {
                $query->whereHas('student', function ($query) use ($specializationId) {
                    $query->where('specialization_id', $specializationId);
                });
            }

            if ($courseId) {
                $query->where('course_id', $courseId);
            }

            if ($semesterNum) {
                $query->where('semester_num', $semesterNum);
            }

            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) {
                $page = 1;
            }
            $skip = ($page - 1) * $pageSize;

            $totalRequest = $query->count();
            $totalPages = ceil($totalRequest / $pageSize);
            $Results = $query->skip($skip)->take($pageSize)->get();

            if ($Results->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Result.index", [
                'Result' => $Results,
                "page" => $page,
                "title" => "قائمة الاعمال فصلية",
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Result.index", [
                'Result' => [],
                "page" => $page,
                "title" => "قائمة الاعمال فصلية",
                "totalPages" => $totalPages,
            ]);
        }
    }

    public function getResultData(Request $request)
    {
        $semesterNum = $request->input('semester_num');
        $courseId = $request->input('course_id');

        $ResultData = Result::where('semester_num', $semesterNum)
            ->where('course_id', $courseId)
            ->with('course', 'student', 'specialization')
            ->get();

        return response()->json($ResultData);
    }

    public function showDeleted()
    {
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalrequest = Result::onlyTrashed()->count();
            $totalPages = ceil($totalrequest / $pageSize);
            $Result = Result::onlyTrashed()->skip($skip)->take($pageSize)->get();
            if ($Result->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Result.index", [
                'data' => $Result,
                "page" => $page,
                "title" => 'بيانت الاعمال فصلية المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Result.index", [
                "page" => $page,
                "title" => 'بيانت الاعمال فصلية المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        }
    }
    public function create()
    {
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'registration' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        $specializationId = htmlspecialchars(strip_tags(request()->get('specialization_id', 0)));
        $semesterNum = htmlspecialchars(strip_tags(request()->get('semester_num', 0)));
        $courseId = htmlspecialchars(strip_tags(request()->get('course_id', 0)));
        $student_id = htmlspecialchars(strip_tags(request()->get('student_id', 1)));
        $student_id = max($student_id, 1);
        $Result = Result::select(
            'academic_work_grade',
            'final_exam_grade',
            'final_grade',
            'status',
            'semester_tasks_id',
        )->where('course_id', $courseId)
            ->where('specialization_id', $specializationId)
            ->where('semester_num', $semesterNum)
            ->where('student_id', $student_id)
            ->with('semesterTasks')
            ->first();
        $student = Student::select(
            'id',
            'full_name',
        )
            ->where('id', '>=', $student_id)
            ->where('specialization_id', $specializationId)
            ->where('semester_num', $semesterNum)
            ->first();
        if (empty($student)) {
            toastr()->warning('وصلت الى اخر طالب');
            return redirect()->route('Result.index');
        }
        $course = Course::select(
            'id',
            'name',
        )
            ->where('id', $courseId)
            ->where('specialization_id', $specializationId)
            ->where('semester_num', $semesterNum)
            ->first();
        try {
            return view("page.Result.create", compact('student', 'course', 'Result'));
        } catch (\Throwable $th) {
            toastr()->error("حدث خطاء ما");
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddResultRequest $request)
    {
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'registration' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $semesterTask = SemesterTask::updateOrCreate(
                [
                    'semester_num' => $request->input('semester_num'),
                    'course_id' => $request->input('course_id'),
                    'specialization_id' => $request->input('specialization_id'),
                    'student_id' => $request->input('student_id'),
                ],
                [
                    'academic_work_grade' => $request->input('SemesterTaskwork', 0),
                ]
            );

            $finalGrade = $semesterTask->final_grade + $request->input('final_exam_grade');
            if ($finalGrade >= 90) {
                $status = 'ممتاز';
            } elseif ($finalGrade >= 80) {
                $status = 'جيد جداً';
            } elseif ($finalGrade >= 70) {
                $status = 'جيد';
            } elseif ($finalGrade >= 50) {
                $status = 'مقبول';
            }  else {
                $status = 'راسب';
            }
            $Result = Result::updateOrCreate(
                [
                    'student_id' => $request->input('student_id'),
                    'course_id' => $request->input('course_id'),
                    'specialization_id' => $request->input('specialization_id'),
                    'semester_num' => $request->input('semester_num'),
                    'semester_tasks_id' => $semesterTask->id,
                ],
                [
                    'academic_work_grade' => $semesterTask->final_grade,
                    'final_exam_grade' => $request->input('final_exam_grade'),
                    'final_grade' => $request->input('final_grade'),
                    'status' => $status,
                ]
            );

            if ($Result->save()) {
                toastr()->success('تمت العملية بنجاح');
                if (!empty(($request->input('Back')))) {
                    return redirect()->route("Result.index");
                }
                return redirect()->route("Result.create", [
                    'specialization_id' => $request->input('specialization_id'),
                    'semester_num' => $request->input('semester_num'),
                    'course_id' => $request->input('course_id'),
                    'student_id' => $request->input('student_id') + 1,
                ]);
            } else {
                toastr()->error('العملية فشلت');
                return redirect()->back();
            }
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
    }
}
