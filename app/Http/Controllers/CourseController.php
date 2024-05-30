<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Specialization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    public function index()
    {
        try {
            $Specialization_id = request('Specialization_id');

            $Specialization = Specialization::select('id', 'name')->where('id', $Specialization_id)->first();
            if (empty($Specialization)) {
                toastr()->error('لا يوجد تخصص');
                return redirect()->back();
            }
            $Course = Course::select(
                'id',
                'name',
                'hours',
                'specialization_id',
                'semester_num',
                'teachers_id'
            )->orderBy('semester_num', 'asc')->where('specialization_id', $Specialization->id)
                ->get();

            if ($Course->isEmpty()) {
                toastr()->error('لا يوجد مواد دراسية');
            }
            return view("page.Course.index", [
                'data' => $Course,
                'Specialization' => $Specialization,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Course.index", [
                'data' => [],
                'Specialization' => $Specialization,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->user_type == 'registration'||$user->user_type == 'student_affairs' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        return view("page.Course.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $user = auth()->user();
        if ($user->user_type == 'registration'||$user->user_type == 'student_affairs' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $AddCourse = new Course();
            $AddCourse->name = htmlspecialchars(strip_tags($request["name"]));
            $AddCourse->hours = htmlspecialchars(strip_tags($request["hours"]));
            $AddCourse->specialization_id = htmlspecialchars(strip_tags($request["specialization_id"]));
            $AddCourse->semester_num = htmlspecialchars(strip_tags($request["semester_num"]));
            if (isset($request["teachers_id"]) && !empty($request["teachers_id"])) {
                $AddCourse->teachers_id = htmlspecialchars(strip_tags($request["teachers_id"]));
            }
            if ($AddCourse->save()) {
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                activity()->performedOn($AddCourse)->event("اضافة مقرر")->causedBy($user)
                    ->log(
                        ' تم اضافة مقرر جديد باسم ' . $AddCourse->name .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Course.index", ['Specialization_id' => $AddCourse->specialization_id]);
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

    public function edit(string $id)
    {
        $user = auth()->user();
        if ($user->user_type == 'registration'||$user->user_type == 'student_affairs' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $Course = Course::find(htmlspecialchars(strip_tags($id)));
            return view("page.Course.edit", [
                'Course' => $Course
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id)
    {
        $user = auth()->user();
        if ($user->user_type == 'registration'||$user->user_type == 'student_affairs' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $updataCourse = Course::findOrFail(htmlspecialchars(strip_tags($request["id"])));
            $updataCourse->name = htmlspecialchars(strip_tags($request["name"]));
            $updataCourse->hours = htmlspecialchars(strip_tags($request["hours"]));
            $updataCourse->specialization_id = htmlspecialchars(strip_tags($request["specialization_id"]));
            $updataCourse->semester_num = htmlspecialchars(strip_tags($request["semester_num"]));
            if (isset($request["teachers_id"]) && !empty($request["teachers_id"])) {
                $updataCourse->teachers_id = htmlspecialchars(strip_tags($request["teachers_id"]));
            }
            if ($updataCourse->save()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل مقرر برقم " . $request["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->performedOn($updataCourse)->event("تعديل مقرر")->causedBy($user)
                    ->log(
                        " تم تعديل المقرر " . $updataCourse->name .
                            " معرف المقرر " . $request["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Course.index", ['Specialization_id' => $updataCourse->specialization_id]);
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
    public function destroy(string $name)
    {
        $user = auth()->user();
        if ($user->user_type == 'registration'||$user->user_type == 'student_affairs' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            //التحقق من الحقول
            $data = request()->validate(
                [
                    "id" => "required|integer|min:1",
                ],
                [
                    'id.required' => "معرف المقرر مطلوب",
                    'id.integer' => "معرف المقرر مطلوب",
                    'id.max' => "اكبر حد لمعرف المقرر 255 حرف",
                    'id.min' => "اقل حد لمعرف المقرر 1",
                ]
            );
            $rowsAffected = Course::destroy(htmlspecialchars(strip_tags($data["id"])));
            if ($rowsAffected) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم حذف مقرر برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->event("حذف مقرر")->causedBy($user)
                    ->log(
                        " تم حذف المقرر " . $name .
                            " معرف المقرر " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error('المقرر غير موجودة ');
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفها لان هناك عمليات مرتبطة بهذه المقرر");
            return redirect()->back();
        }
    }
}
