<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        try {
            $Course = Course::select('id', 'name', 'hours')->get();
            if ($Course->isEmpty()) {
                toastr()->error('لا يوجد مواد دراسية');
            }
            return view("page.Course.index", [
                'data' => $Course
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Course.index");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("page.Course.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        try {

            $AddCourse = new Course();
            $AddCourse->name = htmlspecialchars(strip_tags($request["name"]));
            $AddCourse->hours = htmlspecialchars(strip_tags($request["hours"]));
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
                return redirect()->route("Course.index");
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
    public function update(Request $request, string $id)
    {
        try {

            $data = $request->validate(
                [
                    'name' => 'required|string|min:2',
                    'hours' => 'required|integer',

                ],
                [
                    'id.required' => "معرف المقرر مطلوب",
                    'id.integer' => "معرف المقرر مطلوب",
                    'id.max' => "اكبر حد لمعرف المقرر 255 حرف",
                    'id.min' => "اقل حد لمعرف المقرر 1",
                    'name.required' => "اسم المقرر مطلوب",
                    'name.string' => "يجب ان يكون اسم المقرر نص",
                    'name.max' => "اكبر حد لاسم المقرر 255 حرف",
                    'name.min' => "اقل حد لاسم المقرر 2",
                    'hours.required' => "عدد ساعات المقرر مطلوبة",
                ]
            );
            $updataCourse = Course::findOrFail(htmlspecialchars(strip_tags($request["id"])));
            if ($request->has('name') && $request["name"] != $updataCourse->name) {
                request()->validate(
                    ['name' => 'unique:Courses,name'],
                    ['name.unique' => "يجب ان يكون اسم المقرر فريد"]
                );
                $updataCourse->name = htmlspecialchars(strip_tags($request["name"]));
            }

            $updataCourse->hours = htmlspecialchars(strip_tags($request["hours"]));

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
                return redirect()->route("Course.index");
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
            $rowsAffected = Course::destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
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
