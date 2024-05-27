<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTeacherRequest;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Notifications\Notifications;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class teacherController extends Controller
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
            $totalrequest = Teacher::count();
            $totalPages = ceil($totalrequest / $pageSize);
            $Teacher = Teacher::skip($skip)->take($pageSize)->get();

            if ($Teacher->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Teacher.index", [
                'data' => $Teacher,
                "page" => $page,
                "title" => "قائمة المدرسين",
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Teacher.index", [
                "page" => $page,
                "title" => "قائمة المدرسين",
                "totalPages" => $totalPages,
            ]);
        }
    }

    public function showDeleted()
    {
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalrequest = Teacher::onlyTrashed()->count();
            $totalPages = ceil($totalrequest / $pageSize);
            $Teacher = Teacher::onlyTrashed()->skip($skip)->take($pageSize)->get();
            if ($Teacher->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Teacher.index", [
                'data' => $Teacher,
                "page" => $page,
                "title" => 'بيانت المدرسين المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Teacher.index", [
                "page" => $page,
                "title" => 'بيانت المدرسين المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        }
    }
    public function create()
    {
        try {
            return view("page.Teacher.create");
        } catch (\Throwable $th) {
            toastr()->error("حدث خطاء ما");
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddTeacherRequest $request)
    {

        try {

            $Teacher = new Teacher();
            $Teacher->name = htmlspecialchars(strip_tags($request['name']));
            $Teacher->gender = htmlspecialchars(strip_tags($request['gender']));
            $Teacher->phone_number = htmlspecialchars(strip_tags($request['phone_number']));
            $Teacher->address = htmlspecialchars(strip_tags($request['address']));
            $Teacher->qualification = htmlspecialchars(strip_tags($request['qualification']));

            if ($Teacher->save()) {
                $date = date('H:i Y-m-d');
                $user = User::find(auth()->user()->id);
                activity()->performedOn($Teacher)->event("تعديل مدرس")->causedBy($user)
                    ->log(
                        "تمت إضافة مدرس جديد بإسم " . $Teacher->full_name . " والرقم الاكاديمي " . $Teacher->academic_id . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("teacher.index");
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
            $Teacher = Teacher::select(
                'id',
                'name',
                'qualification',
                'gender',
                'phone_number',
                'address',
            )->where('id', $id)
                ->first();
            if (!$Teacher) {
                toastr()->error("المدرس الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            return view("page.Teacher.edit")->with("teacher", $Teacher);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AddTeacherRequest $request, string $id)
    {
        try {

            $Teacher =  Teacher::find(htmlspecialchars(strip_tags($request['id'])));
            $Teacher->name = htmlspecialchars(strip_tags($request['name']));
            $Teacher->gender = htmlspecialchars(strip_tags($request['gender']));
            $Teacher->phone_number = htmlspecialchars(strip_tags($request['phone_number']));
            $Teacher->address = htmlspecialchars(strip_tags($request['address']));
            $Teacher->qualification = htmlspecialchars(strip_tags($request['qualification']));

            if ($Teacher->save()) {
                $date = date('H:i Y-m-d');
                $user = User::find(auth()->user()->id);
                activity()->performedOn($Teacher)->event("تعديل مدرس")->causedBy($user)
                    ->log(
                        "تمت تعديل مدرس جديد بإسم " . $Teacher->full_name . " والرقم الاكاديمي " . $Teacher->academic_id . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("teacher.index");
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
                'id.required' => "معرف المدرس مدرس",
                'id.integer' => "معرف المدرس يجب أن يكون عدد صحيح",
                'id.min' => "اقل قيمة لمعرف المدرس هي 1",
                'id.max' => "اكبر قيمة لمعرف المدرس هي 255",
            ]);

            $Teacher = Teacher::find(htmlspecialchars(strip_tags($request["id"])));
            if (!$Teacher) {
                toastr()->error('المدرس الامني غير موجود');
                return redirect()->back()
                    ->withInput();
            }
            $rowsAffected = $Teacher->delete();
            if ($rowsAffected) {
                $date = date('H:i Y-m-d');
                // اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات مدرس بإسم " . $Teacher->full_name . " ورقم الاكاديمي " . $Teacher->registration_number . " في تاريخ " . $date,
                );

                activity()->event("أرشفة بلاغ")->causedBy($user)
                    ->log(
                        "تم أرشفة المدرس الامني " .
                            "معرف المدرس " . $id .
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
            toastr()->error("لا يمكنك أرشفة المدرس لأنه هناك عمليات مرتبطة به ");
            return redirect()->back();
        }
    }
}
