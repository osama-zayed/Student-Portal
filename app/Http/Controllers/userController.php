<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Exception;
use App\Models\User as users;
use App\Notifications\Notifications;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Activity;

class userController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show','edit']]); 
    }
    public function index()
    {
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalData = users::count();
            $totalPages = ceil($totalData / $pageSize);
            $users = users::select(
                'id',
                'name',
                'username',
                'password',
                'user_type',
                'user_status',
            )->skip($skip)->take($pageSize)->get();

            if ($users->isEmpty()) {
                toastr()->error('لا يوجد مستخدمين ');
            }

            return view("page.user.index", [
                "data" => $users,
                "page" => $page,
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('حدث خطأ غير متوقع');
            return view("page.user.index", [
                'data' => [],
                "page" => $page,
                "totalPages" => $totalPages,
            ]);
        }
    }

    public function Activity()
    {
        try {
            $pageSize = 500;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalData = Activity::count();
            $totalPages = ceil($totalData / $pageSize);

            $data = Activity::all()->reverse()->skip($skip)->take($pageSize);

            if ($data->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }
            return view('page.user.activity', [
                'data' => $data??[],
                "page" => $page,
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view('page.user.activity', [
                'data' => [],
                "page" => $page,
                "totalPages" => $totalPages,
            ]);
        }
    }

    public function create()
    {
        return view("page.user.create");
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            //التحقق من الحقول
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'username' => 'required|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'user_status' => 'required',
                'user_type' => 'required|string',
            ], [
                "name.required" => "الاسم مطلوب",
                "username.required" => "حقل البريد الإلكتروني مطلوب.",
                "username.unique" => "حقل البريد الإلكتروني مستخدم مسبقًا.",
                "password.required" => "حقل كلمة المرور مطلوب.",
                "password.string" => "حقل كلمة المرور يجب أن يكون نصًا.",
                "password.min" => "حقل كلمة المرور يجب أن يحتوي على الأقل 8 أحرف.",
                "password.confirmed" => "حقل كلمة المرور غير متطابق مع حقل تأكيد كلمة المرور.",
                "user_status.required" => "حقل حالة المستخدم مطلوب.",        
                "user_type.required" => "حقل نوع المستخدم مطلوب.",
            ]);

            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $userCreated = new User();

            $name = htmlspecialchars(strip_tags($request->input('name')));
            $username = htmlspecialchars(strip_tags($request->input('username')));
            $password = bcrypt($request->input('password'));
            $user_status = htmlspecialchars(strip_tags($request->input('user_status')));
            $user_type = htmlspecialchars(strip_tags($request->input('user_type')));

            $userCreated->name = $name;
            $userCreated->username = $username;
            $userCreated->password = $password;
            $userCreated->user_status = $user_status;
            $userCreated->user_type = $user_type;

            // حفظ المستخدم والتحقق من نجاح العملية
            if ($userCreated->save()) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = users::find(auth()->user()->id); 
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم اضافة مستخدم جديد باسم  " .  $userCreated->name
                . " بواسطه الادمن " . $user->name
                . " الوقت والتاريخ " . $date);
                activity()->performedOn($userCreated)->event("اضافة مستخدم")->causedBy($user)
                    ->log(
                        " تم اضافة مستخدم جديد باسم " . $userCreated->name .
                            " بواسطة الادمن " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success("تم الاضافة بنجاح");
                return redirect()->route("user.index");
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
            $pageSize = 500;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalData = Activity::where('causer_id', $id)->count();
            $totalPages = ceil($totalData / $pageSize);

            $data = Activity::where('causer_id', $id)->get()->reverse()->skip($skip)->take($pageSize);
            if ($data->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }
            return view('page.user.activity', [
                'data' => $data,
                "page" => $page,
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view('page.user.activity', [
                'data' => $data,
                "page" => $page,
                "totalPages" => $totalPages,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $User = User::find(htmlspecialchars(strip_tags($id)));
            return view("page.user.edit")->with("User", $User);
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
            $validator = Validator::make($request->all(), [
                "id" => "required|integer",
                'name' => 'nullable|string',
                'username' => 'nullable',
                'password' => 'nullable|string',
                'user_type' => 'string',
            ], [
                "id.required" => "الرقم التعريفي للمستخدم مطلوب",
                "username.unique" => "حقل البريد الإلكتروني مستخدم مسبقًا.",
                "password.string" => "حقل كلمة المرور يجب أن يكون نصًا.",
            ]);
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            // البحث عن المستخدم المراد تعديله
            $userToUpdate = User::findOrFail(htmlspecialchars(strip_tags($request["id"])));
            if (isset($request["username"]) && !empty($request["username"])) {
                if ($userToUpdate->username != $request["username"]) {
                    request()->validate(['username' => 'unique:users'], [
                        "username.unique" => "البريد موجود مسبقا، اختر اسماً آخر",
                    ]);
                }
                $userToUpdate->username = htmlspecialchars(strip_tags($request["username"]));
            }
            if (request()->has('name'))
                $userToUpdate->name = htmlspecialchars(strip_tags($request["name"]));


            if (request()->has('user_status')) {
                $userToUpdate->user_status = htmlspecialchars(strip_tags($request["user_status"]));
            }
            if (request()->has('user_type')) {
                $userToUpdate->user_type = htmlspecialchars(strip_tags($request["user_type"]));
            }
            if (!empty(request()->password)) {
                $request->validate([
                    'password' => 'string|min:8|confirmed',
                ], [
                    "password.required" => "ادخل الرمز",
                    "password.min" => " اقل عدد للرمز 8 خانات",
                    "password.confirmed" => "الرمز غير متطابق",
                    "password.max" => "الحد الاقصى للرمز 255",
                ]);
                $userToUpdate->password = bcrypt(htmlspecialchars(strip_tags($request['password'])));
            }
            if ($userToUpdate->update()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = users::find(auth()->user()->id);
                $date = date('H:i Y-m-d');

                HelperController::NotificationsAdmin(" لقد تم تعديل بيانات المستخدم  " .  $userToUpdate->name 
                . " الوقت والتاريخ " . $date);

                activity()->performedOn($userToUpdate)->event("تعديل مستخدم")->causedBy($user)
                    ->log(
                        " تم تعديل مستخدم باسم " . $userToUpdate->name .
                            " بواسطة الادمن " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success("تم التعديل بنجاح");
                return  redirect()->route("user.index");
            } else {
                toastr()->error("خطأ عند التعديل");
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
                    "id.required" => "الرقم التعريفي للمستخدم مطلوب",
                    'id.integer' => "معرف المستخدم مطلوب",
                    'id.max' => "اكبر حد لمعرف المستخدم 255 حرف",
                    'id.min' => "اقل حد لمعرف المستخدم 1",
                ]
            );
            $rowsAffected = User::destroy(htmlspecialchars(strip_tags($data["id"])));
            if ($rowsAffected) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = users::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
    
                HelperController::NotificationsAdmin(" لقد تم حذف المستخدم برقم  " .  $data["id"]
                . " بواسطه الادمن " . $user->name
                . " الوقت والتاريخ " . $date);
                activity()->event("حذف مستخدم")->causedBy($user)
                    ->log(
                        " تم حذف المستخدم " . $name .
                            " معرف المستخدم " . $data["id"] .
                            " بواسطة الادمن " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error("خطأ عند التعديل");
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفه لان هناك عمليات مرتبطة بهذه المستخدم");
            return redirect()->back();
        }
    }
}
