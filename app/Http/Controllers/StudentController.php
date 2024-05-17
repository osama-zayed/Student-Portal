<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\AddStudentRequest;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Controllers\Controller;
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
            $latestId = Student::max('id') ;
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
            $Student->password = bcrypt($Student->personal_id??$Student->phone_number);
            if (isset($request["image"]) && !empty($request["image"])) {
                $StudentImage = request()->file('image');
                $StudentImagePath = 'images/Student/' .
                    $academicId .  $StudentImage->getClientOriginalName();
                $StudentImage->move(public_path('images/Student/'), $StudentImagePath);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $Student = Student::select(
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
            if (!$Student) {
                toastr()->error("الطالب الامني غير موجود");
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
            $Student = Student::find(htmlspecialchars(strip_tags($request['id'])));
            if (!$Student) {
                toastr()->error("الطالب الامني غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            if (isset($request["registration_number"]) && !empty($request["registration_number"])  && request()->has('registration_number') && $request->registration_number != $Student->registration_number) {
                $validator = Validator::make(
                    $request->all(),
                    ['registration_number' => 'unique:security_wanteds,registration_number'],
                    [
                        'registration_number.unique' => 'رقم الاكاديمي يجب ان يكون فريد',
                    ]
                );
                $Student->registration_number = htmlspecialchars(strip_tags($request->registration_number));
            }
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            // إضافة بيانات الطالب ال
            $Student->registration_number = htmlspecialchars(strip_tags($request['registration_number']));
            $Student->day = htmlspecialchars(strip_tags($request['day']));
            $Student->registration_date = htmlspecialchars(strip_tags($request['registration_date']));
            $Student->full_name = htmlspecialchars(strip_tags($request['full_name']));
            $Student->age = htmlspecialchars(strip_tags($request['age']));
            $Student->event = htmlspecialchars(strip_tags($request['event']));
            $Student->gender = htmlspecialchars(strip_tags($request['gender']));
            $Student->marital_status = htmlspecialchars(strip_tags($request['marital_status']));
            $Student->nationality = htmlspecialchars(strip_tags($request['nationality']));
            $Student->occupation = htmlspecialchars(strip_tags($request['occupation']));
            $Student->place_of_birth = htmlspecialchars(strip_tags($request['place_of_birth']));
            $Student->residence = htmlspecialchars(strip_tags($request['residence']));
            $Student->previous_convictions = htmlspecialchars(strip_tags($request['previous_convictions']));

            if ($Student->save()) {
                // إضافة الإشعار والإضافة إلى سجل العمليات
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAllUser(
                    "لقد تمت تعديل بيانات طالب بإسم " . $Student->full_name . " ورقم الاكاديمي " . $Student->registration_number . " في تاريخ " . $date,
                );

                activity()->performedOn($Student)->event("تعديل طالب")->causedBy($user)
                    ->log(
                        "تم تعديل طالب بإسم " . $Student->full_name . " ورقم الاكاديمي " . $Student->registration_number . " في تاريخ " . $Student->registration_date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
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

            $Student = Student::find(htmlspecialchars(strip_tags($request["id"])));
            if (!$Student) {
                toastr()->error('الطالب الامني غير موجود');
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
