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
                activity()->performedOn($Teacher)->event("إضافة مدرس")->causedBy($user)
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
            if (!$Teacher) {
                toastr()->error("المدرس الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            return view("page.Teacher.edit")->with("Teacher", $Teacher);
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
                'registration_number.required' => 'حقل رقم الاكاديمي مدرس',
                'registration_number.string' => 'حقل رقم الاكاديمي يجب أن يكون نصًا',
                'registration_number.min' => 'حقل رقم الاكاديمي يجب أن يتكون من الحد الأدنى للحروف',
                'day.required' => 'حقل اليوم مدرس',
                'day.string' => 'حقل اليوم يجب أن يكون نصًا',
                'day.min' => 'حقل اليوم يجب أن يتكون من الحد الأدنى للحروف',
                'registration_date.required' => 'حقل تاريخ الاكاديمي مدرس',
                'registration_date.date' => 'حقل تاريخ الاكاديمي يجب أن يكون تاريخًا',
                'full_name.required' => 'حقل اسم المدرس مدرس',
                'full_name.string' => 'حقل اسم المدرس يجب أن يكون نصًا',
                'full_name.min' => 'حقل اسم المدرس يجب أن يتكون من الحد الأدنى للحروف',
                'full_name.regex' => 'اسم المدرس يجب ان يكون رباعي',
                'age.required' => 'حقل العمر مدرس',
                'age.integer' => 'حقل العمر يجب أن يكون رقمًا صحيحًا',
                'age.min' => 'حقل العمر يجب أن يكون أكبر من الصفر',
                'event.required' => 'حقل الحدث مدرس',
                'event.string' => 'حقل الحدث يجب أن يكون نصًا',
                'event.min' => 'حقل الحدث يجب أن يتكون من الحد الأدنى للحروف',
                'gender.required' => 'حقل الجنس مدرس',
                'gender.string' => 'حقل الجنس يجب أن يكون نصًا',
                'gender.min' => 'حقل الجنس يجب أن يتكون من الحد الأدنى للحروف',
                'marital_status.required' => 'حقل الحالة الاجتماعية مدرس',
                'marital_status.string' => 'حقل الحالة الاجتماعية يجب أن يكون نصًا',
                'marital_status.min' => 'حقل الحالة الاجتماعية يجب أن يتكون من الحد الأدنى للحروف',
                'nationality.required' => 'حقل الجنسية مدرس',
                'nationality.string' => 'حقل الجنسية يجب أن يكون نصًا',
                'nationality.min' => 'حقل الجنسية يجب أن يتكون من الحد الأدنى للحروف',
                'occupation.required' => 'حقل المهنة مدرس',
                'occupation.string' => 'حقل المهنة يجب أن يكون نصًا',
                'occupation.min' => 'حقل المهنة يجب أن يتكون من الحد الأدنى للحروف',
                'place_of_birth.required' => 'حقل محل الميلاد مدرس',
                'place_of_birth.string' => 'حقل محل الميلاد يجب أن يكون نصًا',
                'place_of_birth.min' => 'حقل محل الميلاد يجب أن يتكون من الحد الأدنى للحروف',
                'residence.required' => 'حقل السكن مدرس',
                'residence.string' => 'حقل السكن يجب أن يكون نصًا',
                'residence.min' => 'حقل السكن يجب أن يتكون من الحد الأدنى للحروف',
                'previous_convictions.required' => 'حقل السوابق مدرس',
                'previous_convictions.string' => 'حقل السوابق يجب أن يكون نصًا',
                'previous_convictions.min' => 'حقل السوابق يجب أن يتكون من الحد الأدنى للحروف',
            ]);
            $Teacher = Teacher::find(htmlspecialchars(strip_tags($request['id'])));
            if (!$Teacher) {
                toastr()->error("المدرس الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            if (isset($request["registration_number"]) && !empty($request["registration_number"])  && request()->has('registration_number') && $request->registration_number != $Teacher->registration_number) {
                $validator = Validator::make(
                    $request->all(),
                    ['registration_number' => 'unique:security_wanteds,registration_number'],
                    [
                        'registration_number.unique' => 'رقم الاكاديمي يجب ان يكون فريد',
                    ]
                );
                $Teacher->registration_number = htmlspecialchars(strip_tags($request->registration_number));
            }
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            // إضافة بيانات المدرس ال
            $Teacher->registration_number = htmlspecialchars(strip_tags($request['registration_number']));
            $Teacher->day = htmlspecialchars(strip_tags($request['day']));
            $Teacher->registration_date = htmlspecialchars(strip_tags($request['registration_date']));
            $Teacher->full_name = htmlspecialchars(strip_tags($request['full_name']));
            $Teacher->age = htmlspecialchars(strip_tags($request['age']));
            $Teacher->event = htmlspecialchars(strip_tags($request['event']));
            $Teacher->gender = htmlspecialchars(strip_tags($request['gender']));
            $Teacher->marital_status = htmlspecialchars(strip_tags($request['marital_status']));
            $Teacher->nationality = htmlspecialchars(strip_tags($request['nationality']));
            $Teacher->occupation = htmlspecialchars(strip_tags($request['occupation']));
            $Teacher->place_of_birth = htmlspecialchars(strip_tags($request['place_of_birth']));
            $Teacher->residence = htmlspecialchars(strip_tags($request['residence']));
            $Teacher->previous_convictions = htmlspecialchars(strip_tags($request['previous_convictions']));

            if ($Teacher->save()) {
                // إضافة الإشعار والإضافة إلى سجل العمليات
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات مدرس بإسم " . $Teacher->full_name . " ورقم الاكاديمي " . $Teacher->registration_number . " في تاريخ " . $date,
                );

                activity()->performedOn($Teacher)->event("تعديل مدرس")->causedBy($user)
                    ->log(
                        "تم تعديل مدرس بإسم " . $Teacher->full_name . " ورقم الاكاديمي " . $Teacher->registration_number . " في تاريخ " . $Teacher->registration_date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
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
