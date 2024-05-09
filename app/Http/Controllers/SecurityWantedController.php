<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecurityWanted;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Notifications\Notifications;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class SecurityWantedController extends Controller
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
            $totalrequest = SecurityWanted::count();
            $totalPages = ceil($totalrequest / $pageSize);
            $SecurityWanted = SecurityWanted::skip($skip)->take($pageSize)->get();
            if ($SecurityWanted->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.SecurityWanted.index", [
                'data' => $SecurityWanted,
                "page" => $page,
                "title" => "قائمة المطلوبين امنياً",
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.SecurityWanted.index", [
                "page" => $page,
                "title" => "قائمة المطلوبين امنياً",
                "totalPages" => $totalPages,
            ]);
        }
    }

    public function showDeleted()
    {
        if (auth()->user()->user_type == 'user') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalrequest = SecurityWanted::onlyTrashed()->count();
            $totalPages = ceil($totalrequest / $pageSize);
            $SecurityWanted = SecurityWanted::onlyTrashed()->skip($skip)->take($pageSize)->get();
            if ($SecurityWanted->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.SecurityWanted.index", [
                'data' => $SecurityWanted,
                "page" => $page,
                "title" => 'بيانت المطلوبين المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.SecurityWanted.index", [
                "page" => $page,
                "title" => 'بيانت المطلوبين المؤرشفة',
                "totalPages" => $totalPages,
            ]);
        }
    }
    public function create()
    {
        if (auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            return view("page.SecurityWanted.create");
        } catch (\Throwable $th) {
            toastr()->error("حدث خطاء ما");
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            // التحقق من الحقول
            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|integer|min:1|unique:security_wanteds,registration_number',
                'day' => 'required|string|min:2',
                'registration_date' => 'required|date',
                'wanted_name' => 'required|string|regex:/^([\p{Arabic}]+\s){3}[\p{Arabic}]+$/u',
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
                'registration_number.required' => 'حقل رقم القيد مطلوب',
                'registration_number.string' => 'حقل رقم القيد يجب أن يكون نصًا',
                'registration_number.min' => 'حقل رقم القيد يجب أن يتكون من الحد الأدنى للحروف',
                'registration_number.unique' => 'حقل رقم القيد يجب أن يكون فريد',
                'day.required' => 'حقل اليوم مطلوب',
                'day.string' => 'حقل اليوم يجب أن يكون نصًا',
                'day.min' => 'حقل اليوم يجب أن يتكون من الحد الأدنى للحروف',
                'registration_date.required' => 'حقل تاريخ القيد مطلوب',
                'registration_date.date' => 'حقل تاريخ القيد يجب أن يكون تاريخًا',
                'wanted_name.required' => 'حقل اسم المطلوب مطلوب',
                'wanted_name.string' => 'حقل اسم المطلوب يجب أن يكون نصًا',
                'wanted_name.min' => 'حقل اسم المطلوب يجب أن يتكون من الحد الأدنى للحروف',
                'wanted_name.regex' => 'يجب ان يكون الاسام رباعي',
                'age.required' => 'حقل العمر مطلوب',
                'age.integer' => 'حقل العمر يجب أن يكون رقمًا صحيحًا',
                'age.min' => 'حقل العمر يجب أن يكون أكبر من الصفر',
                'event.required' => 'حقل الحدث مطلوب',
                'event.string' => 'حقل الحدث يجب أن يكون نصًا',
                'event.min' => 'حقل الحدث يجب أن يتكون من الحد الأدنى للحروف',
                'gender.required' => 'حقل الجنس مطلوب',
                'gender.string' => 'حقل الجنس يجب أن يكون نصًا',
                'gender.min' => 'حقل الجنس يجب أن يتكون من الحد الأدنى للحروف',
                'marital_status.required' => 'حقل الحالة الاجتماعية مطلوب',
                'marital_status.string' => 'حقل الحالة الاجتماعية يجب أن يكون نصًا',
                'marital_status.min' => 'حقل الحالة الاجتماعية يجب أن يتكون من الحد الأدنى للحروف',
                'nationality.required' => 'حقل الجنسية مطلوب',
                'nationality.string' => 'حقل الجنسية يجب أن يكون نصًا',
                'nationality.min' => 'حقل الجنسية يجب أن يتكون من الحد الأدنى للحروف',
                'occupation.required' => 'حقل المهنة مطلوب',
                'occupation.string' => 'حقل المهنة يجب أن يكون نصًا',
                'occupation.min' => 'حقل المهنة يجب أن يتكون من الحد الأدنى للحروف',
                'place_of_birth.required' => 'حقل محل الميلاد مطلوب',
                'place_of_birth.string' => 'حقل محل الميلاد يجب أن يكون نصًا',
                'place_of_birth.min' => 'حقل محل الميلاد يجب أن يتكون من الحد الأدنى للحروف',
                'residence.required' => 'حقل السكن مطلوب',
                'residence.string' => 'حقل السكن يجب أن يكون نصًا',
                'residence.min' => 'حقل السكن يجب أن يتكون من الحد الأدنى للحروف',
                'previous_convictions.required' => 'حقل السوابق مطلوب',
                'previous_convictions.string' => 'حقل السوابق يجب أن يكون نصًا',
                'previous_convictions.min' => 'حقل السوابق يجب أن يتكون من الحد الأدنى للحروف',
            ]);
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $securityWanted = new SecurityWanted();
            // إضافة بيانات المطلوب الأمني
            $securityWanted->registration_number = htmlspecialchars(strip_tags($request['registration_number']));
            $securityWanted->day = htmlspecialchars(strip_tags($request['day']));
            $securityWanted->registration_date = htmlspecialchars(strip_tags($request['registration_date']));
            $securityWanted->wanted_name = htmlspecialchars(strip_tags($request['wanted_name']));
            $securityWanted->age = htmlspecialchars(strip_tags($request['age']));
            $securityWanted->event = htmlspecialchars(strip_tags($request['event']));
            $securityWanted->gender = htmlspecialchars(strip_tags($request['gender']));
            $securityWanted->marital_status = htmlspecialchars(strip_tags($request['marital_status']));
            $securityWanted->nationality = htmlspecialchars(strip_tags($request['nationality']));
            $securityWanted->occupation = htmlspecialchars(strip_tags($request['occupation']));
            $securityWanted->place_of_birth = htmlspecialchars(strip_tags($request['place_of_birth']));
            $securityWanted->residence = htmlspecialchars(strip_tags($request['residence']));
            $securityWanted->previous_convictions = htmlspecialchars(strip_tags($request['previous_convictions']));

            if ($securityWanted->save()) {
                // إضافة الإشعار والإضافة إلى سجل العمليات
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAllUser(
                    "لقد تمت اضافة مطلوب أمني جديد بإسم " . $securityWanted->wanted_name . " ورقم القيد " . $securityWanted->registration_number . " في تاريخ " . $date,
                );
                activity()->performedOn($securityWanted)->event("إضافة مطلوب أمني")->causedBy($user)
                    ->log(
                        "تمت إضافة مطلوب أمني جديد بإسم " . $securityWanted->wanted_name . " ورقم القيد " . $securityWanted->registration_number . " في تاريخ " . $securityWanted->registration_date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
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
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $securityWanted = SecurityWanted::select(
                'id',
                'registration_number',
                'day',
                'registration_date',
                'wanted_name',
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
            if (!$securityWanted) {
                toastr()->error("المطلوب الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            return view("page.SecurityWanted.edit")->with("SecurityWanted", $securityWanted);
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
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            // التحقق من الحقول
            $validator = Validator::make($request->all(), [
                'registration_number' => 'required|string|min:2',
                'day' => 'required|string|min:2',
                'registration_date' => 'required|date',
                'wanted_name' => 'required|string|regex:/^([\p{Arabic}]+\s){3}[\p{Arabic}]+$/u',
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
                'registration_number.required' => 'حقل رقم القيد مطلوب',
                'registration_number.string' => 'حقل رقم القيد يجب أن يكون نصًا',
                'registration_number.min' => 'حقل رقم القيد يجب أن يتكون من الحد الأدنى للحروف',
                'day.required' => 'حقل اليوم مطلوب',
                'day.string' => 'حقل اليوم يجب أن يكون نصًا',
                'day.min' => 'حقل اليوم يجب أن يتكون من الحد الأدنى للحروف',
                'registration_date.required' => 'حقل تاريخ القيد مطلوب',
                'registration_date.date' => 'حقل تاريخ القيد يجب أن يكون تاريخًا',
                'wanted_name.required' => 'حقل اسم المطلوب مطلوب',
                'wanted_name.string' => 'حقل اسم المطلوب يجب أن يكون نصًا',
                'wanted_name.min' => 'حقل اسم المطلوب يجب أن يتكون من الحد الأدنى للحروف',
                'wanted_name.regex' => 'اسم المطلوب يجب ان يكون رباعي',
                'age.required' => 'حقل العمر مطلوب',
                'age.integer' => 'حقل العمر يجب أن يكون رقمًا صحيحًا',
                'age.min' => 'حقل العمر يجب أن يكون أكبر من الصفر',
                'event.required' => 'حقل الحدث مطلوب',
                'event.string' => 'حقل الحدث يجب أن يكون نصًا',
                'event.min' => 'حقل الحدث يجب أن يتكون من الحد الأدنى للحروف',
                'gender.required' => 'حقل الجنس مطلوب',
                'gender.string' => 'حقل الجنس يجب أن يكون نصًا',
                'gender.min' => 'حقل الجنس يجب أن يتكون من الحد الأدنى للحروف',
                'marital_status.required' => 'حقل الحالة الاجتماعية مطلوب',
                'marital_status.string' => 'حقل الحالة الاجتماعية يجب أن يكون نصًا',
                'marital_status.min' => 'حقل الحالة الاجتماعية يجب أن يتكون من الحد الأدنى للحروف',
                'nationality.required' => 'حقل الجنسية مطلوب',
                'nationality.string' => 'حقل الجنسية يجب أن يكون نصًا',
                'nationality.min' => 'حقل الجنسية يجب أن يتكون من الحد الأدنى للحروف',
                'occupation.required' => 'حقل المهنة مطلوب',
                'occupation.string' => 'حقل المهنة يجب أن يكون نصًا',
                'occupation.min' => 'حقل المهنة يجب أن يتكون من الحد الأدنى للحروف',
                'place_of_birth.required' => 'حقل محل الميلاد مطلوب',
                'place_of_birth.string' => 'حقل محل الميلاد يجب أن يكون نصًا',
                'place_of_birth.min' => 'حقل محل الميلاد يجب أن يتكون من الحد الأدنى للحروف',
                'residence.required' => 'حقل السكن مطلوب',
                'residence.string' => 'حقل السكن يجب أن يكون نصًا',
                'residence.min' => 'حقل السكن يجب أن يتكون من الحد الأدنى للحروف',
                'previous_convictions.required' => 'حقل السوابق مطلوب',
                'previous_convictions.string' => 'حقل السوابق يجب أن يكون نصًا',
                'previous_convictions.min' => 'حقل السوابق يجب أن يتكون من الحد الأدنى للحروف',
            ]);
            $securityWanted = SecurityWanted::find(htmlspecialchars(strip_tags($request['id'])));
            if (!$securityWanted) {
                toastr()->error("المطلوب الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            if (isset($request["registration_number"]) && !empty($request["registration_number"])  && request()->has('registration_number') && $request->registration_number != $securityWanted->registration_number) {
                $validator = Validator::make(
                    $request->all(),
                    ['registration_number' => 'unique:security_wanteds,registration_number'],
                    [
                        'registration_number.unique' => 'رقم القيد يجب ان يكون فريد',
                    ]
                );
                $securityWanted->registration_number = htmlspecialchars(strip_tags($request->registration_number));
            }
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            // إضافة بيانات المطلوب الأمني
            $securityWanted->registration_number = htmlspecialchars(strip_tags($request['registration_number']));
            $securityWanted->day = htmlspecialchars(strip_tags($request['day']));
            $securityWanted->registration_date = htmlspecialchars(strip_tags($request['registration_date']));
            $securityWanted->wanted_name = htmlspecialchars(strip_tags($request['wanted_name']));
            $securityWanted->age = htmlspecialchars(strip_tags($request['age']));
            $securityWanted->event = htmlspecialchars(strip_tags($request['event']));
            $securityWanted->gender = htmlspecialchars(strip_tags($request['gender']));
            $securityWanted->marital_status = htmlspecialchars(strip_tags($request['marital_status']));
            $securityWanted->nationality = htmlspecialchars(strip_tags($request['nationality']));
            $securityWanted->occupation = htmlspecialchars(strip_tags($request['occupation']));
            $securityWanted->place_of_birth = htmlspecialchars(strip_tags($request['place_of_birth']));
            $securityWanted->residence = htmlspecialchars(strip_tags($request['residence']));
            $securityWanted->previous_convictions = htmlspecialchars(strip_tags($request['previous_convictions']));

            if ($securityWanted->save()) {
                // إضافة الإشعار والإضافة إلى سجل العمليات
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات مطلوب أمني بإسم " . $securityWanted->wanted_name . " ورقم القيد " . $securityWanted->registration_number . " في تاريخ " . $date,
                );

                activity()->performedOn($securityWanted)->event("تعديل مطلوب أمني")->causedBy($user)
                    ->log(
                        "تم تعديل مطلوب أمني بإسم " . $securityWanted->wanted_name . " ورقم القيد " . $securityWanted->registration_number . " في تاريخ " . $securityWanted->registration_date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
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
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $request = request()->validate([
                "id" => "required|integer|min:1|max:255",
            ], [
                'id.required' => "معرف المطلوب مطلوب",
                'id.integer' => "معرف المطلوب يجب أن يكون عدد صحيح",
                'id.min' => "اقل قيمة لمعرف المطلوب هي 1",
                'id.max' => "اكبر قيمة لمعرف المطلوب هي 255",
            ]);

            $SecurityWanted = SecurityWanted::find(htmlspecialchars(strip_tags($request["id"])));
            if (!$SecurityWanted) {
                toastr()->error('المطلوب الامني غير موجود');
                return redirect()->back()
                    ->withInput();
            }
            $rowsAffected = $SecurityWanted->delete();
            if ($rowsAffected) {
                $date = date('H:i Y-m-d');
                // اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات مطلوب أمني بإسم " . $SecurityWanted->wanted_name . " ورقم القيد " . $SecurityWanted->registration_number . " في تاريخ " . $date,
                );

                activity()->event("أرشفة بلاغ")->causedBy($user)
                    ->log(
                        "تم أرشفة المطلوب الامني " .
                            "معرف المطلوب " . $id .
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
            toastr()->error("لا يمكنك أرشفة المطلوب لأنه هناك عمليات مرتبطة به ");
            return redirect()->back();
        }
    }
}
