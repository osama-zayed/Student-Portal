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
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Notifications\Notifications;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

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

        try {
            $SemesterTask = SemesterTask::updateOrCreate(
                [
                    'semester_num' => $request->input('semester_num'),
                    'course_id' => $request->input('course_id'),
                    'specialization_id' => $request->input('specialization_id'),
                    'student_id' => $request->input('student_id'),
                ],
   
            );

            $Result = Result::updateOrCreate(
                [
                    'student_id' => $request->input('student_id'),
                    'course_id' => $request->input('course_id'),
                    'specialization_id' => $request->input('specialization_id'),
                    'semester_num' => $request->input('semester_num'),
                    'semester_tasks_id' => $SemesterTask->id,
                ],
                [
                    'academic_work_grade' => $request->input('academic_work_grade'),
                    'final_exam_grade' => $request->input('final_exam_grade'),
                    'final_grade' => $request->input('final_grade'),
                    'status'=>"ناجح",
                ]
            );

            if ($Result->save()) {
                toastr()->success('تمت العملية بنجاح');
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
        try {
            $Result = Result::select(
                'id',
                'registration_number',
                'day',
                'registration_date',
                'full_name',
                'age',
                'event',
                'gender',
                'marital_status',
                'nationality',
                'occupation',
                'place_of_birth',
                'residence',
                'previous_convictions'
            )->where('id', $id)
                ->first();
            if (!$Result) {
                toastr()->error("الطالب الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            return view("page.Result.edit")->with("Result", $Result);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // التحقق من الحقول
            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|min:2',
                'day' => 'required|string|min:2',
                'registration_date' => 'required|date',
                'full_name' => 'required|string|regex:/^([\p{Arabic}]+\s){3}[\p{Arabic}]+$/u',
                'age' => 'required|integer|min:1',
                'event' => 'required|string|min:2',
                'gender' => 'required|string|min:2',
                'marital_status' => 'required|string|min:2',
                'nationality' => 'required|string|min:2',
                'occupation' => 'required|string|min:2',
                'place_of_birth' => 'required|string|min:2',
                'residence' => 'required|string|min:2',
                'previous_convictions' => 'required|string|min:2',
            ], [
                'registration_number.required' => 'حقل رقم الاكاديمي طالب',
                'registration_number.string' => 'حقل رقم الاكاديمي يجب أن يكون نصًا',
                'registration_number.min' => 'حقل رقم الاكاديمي يجب أن يتكون من الحد الأدنى للحروف',
                'day.required' => 'حقل اليوم طالب',
                'day.string' => 'حقل اليوم يجب أن يكون نصًا',
                'day.min' => 'حقل اليوم يجب أن يتكون من الحد الأدنى للحروف',
                'registration_date.required' => 'حقل تاريخ الاكاديمي طالب',
                'registration_date.date' => 'حقل تاريخ الاكاديمي يجب أن يكون تاريخًا',
                'full_name.required' => 'حقل اسم الطالب طالب',
                'full_name.string' => 'حقل اسم الطالب يجب أن يكون نصًا',
                'full_name.min' => 'حقل اسم الطالب يجب أن يتكون من الحد الأدنى للحروف',
                'full_name.regex' => 'اسم الطالب يجب ان يكون رباعي',
                'age.required' => 'حقل العمر طالب',
                'age.integer' => 'حقل العمر يجب أن يكون رقمًا صحيحًا',
                'age.min' => 'حقل العمر يجب أن يكون أكبر من الصفر',
                'event.required' => 'حقل الحدث طالب',
                'event.string' => 'حقل الحدث يجب أن يكون نصًا',
                'event.min' => 'حقل الحدث يجب أن يتكون من الحد الأدنى للحروف',
                'gender.required' => 'حقل الجنس طالب',
                'gender.string' => 'حقل الجنس يجب أن يكون نصًا',
                'gender.min' => 'حقل الجنس يجب أن يتكون من الحد الأدنى للحروف',
                'marital_status.required' => 'حقل الحالة الاجتماعية طالب',
                'marital_status.string' => 'حقل الحالة الاجتماعية يجب أن يكون نصًا',
                'marital_status.min' => 'حقل الحالة الاجتماعية يجب أن يتكون من الحد الأدنى للحروف',
                'nationality.required' => 'حقل الجنسية طالب',
                'nationality.string' => 'حقل الجنسية يجب أن يكون نصًا',
                'nationality.min' => 'حقل الجنسية يجب أن يتكون من الحد الأدنى للحروف',
                'occupation.required' => 'حقل المهنة طالب',
                'occupation.string' => 'حقل المهنة يجب أن يكون نصًا',
                'occupation.min' => 'حقل المهنة يجب أن يتكون من الحد الأدنى للحروف',
                'place_of_birth.required' => 'حقل محل الميلاد طالب',
                'place_of_birth.string' => 'حقل محل الميلاد يجب أن يكون نصًا',
                'place_of_birth.min' => 'حقل محل الميلاد يجب أن يتكون من الحد الأدنى للحروف',
                'residence.required' => 'حقل السكن طالب',
                'residence.string' => 'حقل السكن يجب أن يكون نصًا',
                'residence.min' => 'حقل السكن يجب أن يتكون من الحد الأدنى للحروف',
                'previous_convictions.required' => 'حقل السوابق طالب',
                'previous_convictions.string' => 'حقل السوابق يجب أن يكون نصًا',
                'previous_convictions.min' => 'حقل السوابق يجب أن يتكون من الحد الأدنى للحروف',
            ]);
            $Result = Result::find(htmlspecialchars(strip_tags($request['id'])));
            if (!$Result) {
                toastr()->error("الطالب الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            if (isset($request["registration_number"]) && !empty($request["registration_number"])  && request()->has('registration_number') && $request->registration_number != $Result->registration_number) {
                $validator = Validator::make(
                    $request->all(),
                    ['registration_number' => 'unique:security_wanteds,registration_number'],
                    [
                        'registration_number.unique' => 'رقم الاكاديمي يجب ان يكون فريد',
                    ]
                );
                $Result->registration_number = htmlspecialchars(strip_tags($request->registration_number));
            }
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            // إضافة بيانات الطالب ال
            $Result->registration_number = htmlspecialchars(strip_tags($request['registration_number']));
            $Result->day = htmlspecialchars(strip_tags($request['day']));
            $Result->registration_date = htmlspecialchars(strip_tags($request['registration_date']));
            $Result->full_name = htmlspecialchars(strip_tags($request['full_name']));
            $Result->age = htmlspecialchars(strip_tags($request['age']));
            $Result->event = htmlspecialchars(strip_tags($request['event']));
            $Result->gender = htmlspecialchars(strip_tags($request['gender']));
            $Result->marital_status = htmlspecialchars(strip_tags($request['marital_status']));
            $Result->nationality = htmlspecialchars(strip_tags($request['nationality']));
            $Result->occupation = htmlspecialchars(strip_tags($request['occupation']));
            $Result->place_of_birth = htmlspecialchars(strip_tags($request['place_of_birth']));
            $Result->residence = htmlspecialchars(strip_tags($request['residence']));
            $Result->previous_convictions = htmlspecialchars(strip_tags($request['previous_convictions']));

            if ($Result->save()) {
                // إضافة الإشعار والإضافة إلى سجل العمليات
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات طالب بإسم " . $Result->full_name . " ورقم الاكاديمي " . $Result->registration_number . " في تاريخ " . $date,
                );

                activity()->performedOn($Result)->event("تعديل طالب")->causedBy($user)
                    ->log(
                        "تم تعديل طالب بإسم " . $Result->full_name . " ورقم الاكاديمي " . $Result->registration_number . " في تاريخ " . $Result->registration_date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );
                // نهاية كود إضافة الإشعار والإضافة إلى سجل العمليات

                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Security_wanted.index");
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
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $request = request()->validate([
                "id" => "required|integer|min:1|max:255",
            ], [
                'id.required' => "معرف الطالب طالب",
                'id.integer' => "معرف الطالب يجب أن يكون عدد صحيح",
                'id.min' => "اقل قيمة لمعرف الطالب هي 1",
                'id.max' => "اكبر قيمة لمعرف الطالب هي 255",
            ]);

            $Result = Result::find(htmlspecialchars(strip_tags($request["id"])));
            if (!$Result) {
                toastr()->error('الطالب الامني غير موجود');
                return redirect()->back()
                    ->withInput();
            }
            $rowsAffected = $Result->delete();
            if ($rowsAffected) {
                $date = date('H:i Y-m-d');
                // اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات طالب بإسم " . $Result->full_name . " ورقم الاكاديمي " . $Result->registration_number . " في تاريخ " . $date,
                );

                activity()->event("أرشفة بلاغ")->causedBy($user)
                    ->log(
                        "تم أرشفة الطالب الامني " .
                            "معرف الطالب " . $id .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date
                    );
                // نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success("تم الأرشفة بنجاح");
                return redirect()->back();
            } else {
                toastr()->error("العملية فشلت");
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لا يمكنك أرشفة الطالب لأنه هناك عمليات مرتبطة به ");
            return redirect()->back();
        }
    }
}
