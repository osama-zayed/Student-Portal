<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class CollegeController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        try {
            $College = College::select('id', 'name')->get();
            if ($College->isEmpty()) {
                toastr()->error('لا يوجد كليات');
            }
            return view("page.College.index", [
                'data' => $College
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.College.index");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("page.College.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                [
                    'name' => 'required|string|unique:college,name|min:2',
                ],
                [
                    'name.required' => "اسم الكلية مطلوب",
                    'name.string' => "يجب ان يكون اسم الكلية نص",
                    'name.max' => "اكبر حد لاسم الكلية 255 حرف",
                    'name.min' => "اقل حد لاسم الكلية 2",
                    'name.unique' => "يجب ان يكون اسم الكلية فريد",
                ]
            ]);
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $AddCollege = new College();
            //اضافة اسم الكلية وازالة النصوص الضارة منه
            $AddCollege->name = htmlspecialchars(strip_tags($request["name"]));
            if ($AddCollege->save()) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');

                HelperController::NotificationsAdmin(" لقد تمت اضافة كلية جديد باسم " . $AddCollege->name
                    . " الوقت والتاريخ " . $date);

                activity()->performedOn($AddCollege)->event("اضافة كلية")->causedBy($user)
                    ->log(
                        ' تم اضافة كلية جديد باسم ' . $AddCollege->name .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("College.index");
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
            $College = College::find(htmlspecialchars(strip_tags($id)));
            return view("page.College.edit", [
                'College' => $College
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
                    "id" => "required|integer|min:1",
                    'name' => 'required|string|min:2',

                ],
                [
                    'id.required' => "معرف الكلية مطلوب",
                    'id.integer' => "معرف الكلية مطلوب",
                    'id.max' => "اكبر حد لمعرف الكلية 255 حرف",
                    'id.min' => "اقل حد لمعرف الكلية 1",
                    'name.required' => "اسم الكلية مطلوب",
                    'name.string' => "يجب ان يكون اسم الكلية نص",
                    'name.max' => "اكبر حد لاسم الكلية 255 حرف",
                    'name.min' => "اقل حد لاسم الكلية 2",
                ]
            );

            $updataCollege = College::findOrFail(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($request->has('name') && $data["name"] != $updataCollege->name) {
                request()->validate(
                    ['name' => 'unique:college,name'],
                    ['name.unique' => "يجب ان يكون اسم الكلية فريد"]
                );
            }
            $updataCollege->name = htmlspecialchars(strip_tags($data["name"]));
            if ($updataCollege->save()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل كلية برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->performedOn($updataCollege)->event("تعديل كلية")->causedBy($user)
                    ->log(
                        " تم تعديل الكلية " . $updataCollege->name .
                            " معرف الكلية " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("College.index");
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
                    'id.required' => "معرف الكلية مطلوب",
                    'id.integer' => "معرف الكلية مطلوب",
                    'id.max' => "اكبر حد لمعرف الكلية 255 حرف",
                    'id.min' => "اقل حد لمعرف الكلية 1",
                ]
            );
            $rowsAffected = College::destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($rowsAffected) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم حذف كلية برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->event("حذف كلية")->causedBy($user)
                    ->log(
                        " تم حذف الكلية " . $name .
                            " معرف الكلية " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error('الكلية غير موجودة ');
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفها لان هناك عمليات مرتبطة بهذه الكلية");
            return redirect()->back();
        }
    }
}
