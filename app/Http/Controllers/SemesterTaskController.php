<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemesterTask\AddSemesterTaskRequest;
use Illuminate\Http\Request;
use App\Models\SemesterTask;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Result;
use App\Models\Student;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Notifications\Notifications;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class SemesterTaskController extends Controller
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

            $query = SemesterTask::query()->with('course', 'student', 'specialization');

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
            $semesterTasks = $query->skip($skip)->take($pageSize)->get();

            if ($semesterTasks->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.SemesterTask.index", [
                'SemesterTask' => $semesterTasks,
                "page" => $page,
                "title" => "قائمة الاعمال فصلية",
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.SemesterTask.index", [
                'SemesterTask' => [],
                "page" => $page,
                "title" => "قائمة الاعمال فصلية",
                "totalPages" => $totalPages,
            ]);
        }
    }

    public function getSemesterTaskData(Request $request)
    {
        $semesterNum = $request->input('semester_num');
        $courseId = $request->input('course_id');

        $semesterTaskData = SemesterTask::where('semester_num', $semesterNum)
            ->where('course_id', $courseId)
            ->with('course', 'student', 'specialization')
            ->get();
        return response()->json($semesterTaskData);
    }

    public function showDeleted()
    {
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalrequest = SemesterTask::onlyTrashed()->count();
            $totalPages = ceil($totalrequest / $pageSize);
            $SemesterTask = SemesterTask::onlyTrashed()->skip($skip)->take($pageSize)->get();
            if ($SemesterTask->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.SemesterTask.index", [
                'data' => $SemesterTask,
                "page" => $page,
                "title" => 'بيانت الاعمال فصلية المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.SemesterTask.index", [
                "page" => $page,
                "title" => 'بيانت الاعمال فصلية المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        }
    }
    public function create()
    {
        $specializationId = htmlspecialchars(strip_tags(request()->get('specialization_id', 0)));
        $semesterNum = htmlspecialchars(strip_tags(request()->get('semester_num', 0)));
        $courseId = htmlspecialchars(strip_tags(request()->get('course_id', 0)));
        $student_id = htmlspecialchars(strip_tags(request()->get('student_id', 1)));
        $student_id = max($student_id, 1);
        $SemesterTask = SemesterTask::select(
            'academic_work_grade',
            'attendance',
            'midterm_grade',
            'practicality_grade',
            'final_grade',
        )->where('course_id', $courseId)
            ->where('specialization_id', $specializationId)
            ->where('semester_num', $semesterNum)
            ->where('student_id', $student_id)
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
            return redirect()->route('SemesterTask.index');
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
            return view("page.SemesterTask.create", compact('student', 'course', 'SemesterTask'));
        } catch (\Throwable $th) {
            toastr()->error("حدث خطاء ما");
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddSemesterTaskRequest $request)
    {
        try {
            $semesterTask = $this->createOrUpdateSemesterTask($request);

            $result = $this->createOrUpdateResult($request, $semesterTask);

            $this->updateResult($result, $semesterTask);

            if ($semesterTask->save()) {
                toastr()->success('تمت العملية بنجاح');
                if (!empty(($request->input('Back')))) {
                    return redirect()->route("SemesterTask.index");
                }
                return $this->redirectToNextStudent($request);
            } else {
                toastr()->error('العملية فشلت');
                return redirect()->back();
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    private function createOrUpdateSemesterTask(AddSemesterTaskRequest $request)
    {
        return SemesterTask::updateOrCreate(
            [
                'student_id' => $request->input('student_id'),
                'course_id' => $request->input('course_id'),
                'specialization_id' => $request->input('specialization_id'),
                'semester_num' => $request->input('semester_num'),
            ],
            [
                'academic_work_grade' => $request->input('academic_work_grade'),
                'attendance' => $request->input('attendance'),
                'midterm_grade' => $request->input('midterm_grade'),
                'practicality_grade' => $request->input('practicality_grade'),
                'final_grade' => $request->input('final_grade'),
            ]
        );
    }

    private function createOrUpdateResult(AddSemesterTaskRequest $request, SemesterTask $semesterTask)
    {
        return Result::updateOrCreate(
            [
                'student_id' => $request->input('student_id'),
                'course_id' => $request->input('course_id'),
                'specialization_id' => $request->input('specialization_id'),
                'semester_num' => $request->input('semester_num'),
                'semester_tasks_id' => $semesterTask->id,
            ],
            [
                'academic_work_grade' => $semesterTask->final_grade,
            ]
        );
    }

    private function updateResult(Result $result, SemesterTask $semesterTask)
    {
        $finalGrade = $semesterTask->final_grade + $result->final_exam_grade;
        if ($finalGrade >= 100) {
            toastr()->error('المجموع تعدى ال100 ');
            return redirect()->back();
        } elseif ($finalGrade >= 90) {
            $status = 'ممتاز';
        } elseif ($finalGrade >= 80) {
            $status = 'جيد جداً';
        } elseif ($finalGrade >= 70) {
            $status = 'جيد';
        } elseif ($finalGrade >= 50) {
            $status = 'مقبول';
        } else {
            $status = 'راسب';
        }
        $result->update([
            'final_grade' => $result->academic_work_grade + $result->final_exam_grade,
            'final_exam_grade' => $result->final_exam_grade,
            'status' => $status,
        ]);
    }

    private function redirectToNextStudent(AddSemesterTaskRequest $request)
    {
        return redirect()->route("SemesterTask.create", [
            'specialization_id' => $request->input('specialization_id'),
            'semester_num' => $request->input('semester_num'),
            'course_id' => $request->input('course_id'),
            'student_id' => $request->input('student_id') + 1,
        ]);
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
