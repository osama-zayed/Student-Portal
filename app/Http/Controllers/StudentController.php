<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\AddStudentRequest;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\SemesterTask;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Notifications\Notifications;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalrequest = Student::count();
            $totalPages = ceil($totalrequest / $pageSize);
            $Student = Student::skip($skip)->take($pageSize)->get();

            if ($Student->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Student.index", [
                'data' => $Student,
                "page" => $page,
                "title" => "قائمة الطلاب",
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Student.index", [
                "page" => $page,
                "title" => "قائمة الطلاب",
                "totalPages" => $totalPages,
            ]);
        }
    }
    public function semesterTask(Request $request)
    {
        try {

            $SemesterTask = SemesterTask::select(
                'id',
                'specialization_id',
                'course_id',
                'student_id',
                'semester_num',
                'academic_work_grade',
                'attendance',
                'midterm_grade',
                'practicality_grade',
                'final_grade',
            )
                ->where('specialization_id', $request->get('specialization_id'))
                ->where('student_id', $request->get('student_id'))
                ->where('semester_num', $request->get('semesterNum'))
                ->with('course')
                ->get();

            $transformedData = $SemesterTask->map(function ($SemesterTask, $num) {
                return [
                    'id' => ++$num,
                    'course_name' => $SemesterTask->course->name,
                    'course_id' => $SemesterTask->course_id,
                    'semester_num' => $SemesterTask->semester_num,
                    'academic_work_grade' => $SemesterTask->academic_work_grade,
                    'attendance' => $SemesterTask->attendance,
                    'midterm_grade' => $SemesterTask->midterm_grade,
                    'practicality_grade' => $SemesterTask->practicality_grade,
                    'final_grade' => $SemesterTask->final_grade,
                ];
            });

            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
            if ($SemesterTask->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد نتائج'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function ResultData(Request $request)
    {
        try {

            $SemesterTask = Result::select(
                'id',
                'student_id',
                'specialization_id',
                'semester_tasks_id',
                'semester_num',
                'course_id',
                'academic_work_grade',
                'final_exam_grade',
                'final_grade',
                'status',
            )
                ->where('specialization_id', $request->get('specialization_id'))
                ->where('student_id', $request->get('student_id'))
                ->where('semester_num', $request->get('semesterNum'))
                ->with('course')
                ->get();

            $transformedData = $SemesterTask->map(function ($SemesterTask, $num) {
                return [
                    'id' => ++$num,
                    'course_name' => $SemesterTask->course->name,
                    'course_id' => $SemesterTask->course_id,
                    'semester_num' => $SemesterTask->semester_num,
                    'academic_work_grade' => $SemesterTask->academic_work_grade,
                    'final_exam_grade' => $SemesterTask->final_exam_grade,
                    'final_grade' => $SemesterTask->final_grade,
                    'status' => $SemesterTask->status,
                ];
            });

            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
            if ($SemesterTask->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد نتائج'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function showDeleted()
    {
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalrequest = Student::onlyTrashed()->count();
            $totalPages = ceil($totalrequest / $pageSize);
            $Student = Student::onlyTrashed()->skip($skip)->take($pageSize)->get();
            if ($Student->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Student.index", [
                'data' => $Student,
                "page" => $page,
                "title" => 'بيانت الطلاب المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Student.index", [
                "page" => $page,
                "title" => 'بيانت الطلاب المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        }
    }
    public function create()
    {
        try {
            return view("page.Student.create");
        } catch (\Throwable $th) {
            toastr()->error("حدث خطاء ما");
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddStudentRequest $request)
    {

        try {

            $Student = new Student();
            $latestId = Student::max('id');
            $Student->full_name = htmlspecialchars(strip_tags($request['full_name']));
            $Student->personal_id = htmlspecialchars(strip_tags($request['personal_id']));
            $academicId = date('Ymd') . ++$latestId;
            $Student->academic_id = $academicId;
            $Student->phone_number = htmlspecialchars(strip_tags($request['phone_number']));
            $Student->relative_phone_number = htmlspecialchars(strip_tags($request['relative_phone_number']));
            $Student->date_of_birth = htmlspecialchars(strip_tags($request['date_of_birth']));
            $Student->place_of_birth = htmlspecialchars(strip_tags($request['place_of_birth']));
            $Student->gender = htmlspecialchars(strip_tags($request['gender']));
            $Student->nationality = htmlspecialchars(strip_tags($request['nationality']));
            $Student->educational_qualification = htmlspecialchars(strip_tags($request['educational_qualification']));
            $Student->high_school_grade = htmlspecialchars(strip_tags($request['high_school_grade']));
            $Student->school_graduation_date = htmlspecialchars(strip_tags($request['school_graduation_date']));
            $Student->discount_percentage = htmlspecialchars(strip_tags($request['discount_percentage']));
            $Student->college_id = htmlspecialchars(strip_tags($request['college_id']));
            $Student->specialization_id = htmlspecialchars(strip_tags($request['specialization_id']));
            $Student->password = bcrypt($Student->personal_id ?? $Student->phone_number);
            if (isset($request["image"]) && !empty($request["image"])) {
                $StudentImage = request()->file('image');
                $StudentImagePath = 'images/Student/' .
                    $academicId .  $StudentImage->getClientOriginalName();
                $StudentImage->move(public_path('images/Student/'), $StudentImagePath);
                $Student->image = $StudentImagePath;
            } else {
                if ($Student->gender == 'ذكر') {
                    $StudentImagePath = 'images/Student/Male.jpg';
                } else {
                    $StudentImagePath = 'images/Student/Female.jpg';
                }
                $Student->image = $StudentImagePath;
            }
            if ($Student->save()) {
                $date = date('H:i Y-m-d');
                $user = User::find(auth()->user()->id);
                activity()->performedOn($Student)->event("إضافة طالب")->causedBy($user)
                    ->log(
                        "تمت إضافة طالب جديد بإسم " . $Student->full_name . " والرقم الاكاديمي " . $Student->academic_id . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Student.index");
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
        try {
            $Student = Student::select(
                'id',
                'full_name',
                'personal_id',
                'academic_id',
                'phone_number',
                'relative_phone_number',
                'date_of_birth',
                'place_of_birth',
                'gender',
                'nationality',
                'educational_qualification',
                'high_school_grade',
                'school_graduation_date',
                'discount_percentage',
                'college_id',
                'specialization_id',
                'password',
                'semester_num',
                'user_status',
                'image',
            )->where('id', $id)
                ->first();
            if (!$Student) {
                toastr()->error("الطالب غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            return view("page.Student.show")->with("Student", $Student);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $Student = Student::select(
                'id',
                'full_name',
                'personal_id',
                'academic_id',
                'phone_number',
                'relative_phone_number',
                'date_of_birth',
                'place_of_birth',
                'gender',
                'nationality',
                'educational_qualification',
                'high_school_grade',
                'school_graduation_date',
                'discount_percentage',
                'college_id',
                'specialization_id',
                'password',
                'semester_num',
                'user_status',
                'image',
            )->where('id', $id)
                ->first();
            if (!$Student) {
                toastr()->error("الطالب غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            return view("page.Student.edit")->with("Student", $Student);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AddStudentRequest $request, string $id)
    {
        try {
            $Student =  Student::find(htmlspecialchars(strip_tags($request['id'])));
            $Student->full_name = htmlspecialchars(strip_tags($request['full_name']));
            $Student->personal_id = htmlspecialchars(strip_tags($request['personal_id']));
            $Student->phone_number = htmlspecialchars(strip_tags($request['phone_number']));
            $Student->relative_phone_number = htmlspecialchars(strip_tags($request['relative_phone_number']));
            $Student->date_of_birth = htmlspecialchars(strip_tags($request['date_of_birth']));
            $Student->place_of_birth = htmlspecialchars(strip_tags($request['place_of_birth']));
            $Student->gender = htmlspecialchars(strip_tags($request['gender']));
            $Student->nationality = htmlspecialchars(strip_tags($request['nationality']));
            $Student->educational_qualification = htmlspecialchars(strip_tags($request['educational_qualification']));
            $Student->high_school_grade = htmlspecialchars(strip_tags($request['high_school_grade']));
            $Student->school_graduation_date = htmlspecialchars(strip_tags($request['school_graduation_date']));
            $Student->discount_percentage = htmlspecialchars(strip_tags($request['discount_percentage']));
            $Student->college_id = htmlspecialchars(strip_tags($request['college_id']));
            $Student->specialization_id = htmlspecialchars(strip_tags($request['specialization_id']));
            $Student->password = bcrypt($Student->personal_id ?? $Student->phone_number);
            if (isset($request["image"]) && !empty($request["image"])) {
                $StudentImage = request()->file('image');
                $StudentImagePath = 'images/Student/' .
                    $Student->academic_id .  $StudentImage->getClientOriginalName();
                $StudentImage->move(public_path('images/Student/'), $StudentImagePath);
                $Student->image = $StudentImagePath;
            }
            if ($Student->save()) {
                $date = date('H:i Y-m-d');
                $user = User::find(auth()->user()->id);
                activity()->performedOn($Student)->event("تعديل طالب")->causedBy($user)
                    ->log(
                        "تمت تعديل الطالب " . $Student->full_name . " والرقم الاكاديمي " . $Student->academic_id . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Student.index");
            } else {
                toastr()->error('العملية فشلت');
                return redirect()->back();
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }
    public function StudentStatus(Request $request)
    {
        try {
            $Student =  Student::find(htmlspecialchars(strip_tags($request['id'])));
            $Student->user_status = $request['Student_status'];
            if ($Student->save()) {
                $date = date('H:i Y-m-d');
                $user = User::find(auth()->user()->id);
                activity()->performedOn($Student)->event("تعديل طالب")->causedBy($user)
                    ->log(
                        "تمت تعديل الطالب " . $Student->full_name . " والرقم الاكاديمي " . $Student->academic_id . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Student.index");
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

            $Student = Student::find(htmlspecialchars(strip_tags($request["id"])));
            if (!$Student) {
                toastr()->error('الطالب غير موجود');
                return redirect()->back()
                    ->withInput();
            }
            $rowsAffected = $Student->delete();
            if ($rowsAffected) {
                $date = date('H:i Y-m-d');
                // اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات طالب بإسم " . $Student->full_name . " ورقم الاكاديمي " . $Student->registration_number . " في تاريخ " . $date,
                );

                activity()->event("أرشفة طالب")->causedBy($user)
                    ->log(
                        "تم أرشفة الطالب " .
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
