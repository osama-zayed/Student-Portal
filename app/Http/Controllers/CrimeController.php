<?php

namespace App\Http\Controllers;

use App\Models\Crime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;


class CrimeController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $Crime = Crime::select('id', 'name')->get();
            if ($Crime->isEmpty()) {
                toastr()->error('لا يوجد جرائم');
            }
            return view("page.Crime.index", [
                'data' => $Crime
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Crime.index");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        return view("page.Crime.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
                toastr()->error("غير مصرح لك");
                return redirect()->back();
            }
            $data =  $request->validate(
                [
                    'name' => 'required|string|unique:crimes,name|min:2',
                ],
                [
                    'name.required' => "اسم الجريمة مطلوب",
                    'name.string' => "يجب ان يكون اسم الجريمة نص",
                    'name.max' => "اكبر حد لاسم الجريمة 255 حرف",
                    'name.min' => "اقل حد لاسم الجريمة 2",
                    'name.unique' => "يجب ان يكون اسم الجريمة فريد",
                ]
            );
            $AddCrime = new Crime();
            //اضافة اسم الجريمة وازالة النصوص الضارة منه
            $AddCrime->name = htmlspecialchars(strip_tags($data["name"]));
            if ($AddCrime->save()) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');

                HelperController::NotificationsAdmin(" لقد تمت اضافة جريمة جديد باسم " . $AddCrime->name
                    . " الوقت والتاريخ " . $date);

                activity()->performedOn($AddCrime)->event("اضافة جريمة")->causedBy($user)
                    ->log(
                        ' تم اضافة جريمة جديد باسم ' . $AddCrime->name .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Crime.index");
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
            if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
                toastr()->error("غير مصرح لك");
                return redirect()->back();
            }
            $Crime = Crime::find(htmlspecialchars(strip_tags($id)));
            return view("page.Crime.edit", [
                'Crime' => $Crime
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
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $data = $request->validate(
                [
                    "id" => "required|integer|min:1",
                    'name' => 'required|string|min:2',

                ],
                [
                    'id.required' => "معرف الجريمة مطلوب",
                    'id.integer' => "معرف الجريمة مطلوب",
                    'id.max' => "اكبر حد لمعرف الجريمة 255 حرف",
                    'id.min' => "اقل حد لمعرف الجريمة 1",
                    'name.required' => "اسم الجريمة مطلوب",
                    'name.string' => "يجب ان يكون اسم الجريمة نص",
                    'name.max' => "اكبر حد لاسم الجريمة 255 حرف",
                    'name.min' => "اقل حد لاسم الجريمة 2",
                ]
            );

            $updataCrime = Crime::findOrFail(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($request->has('name') && $data["name"] != $updataCrime->name) {
                request()->validate(
                    ['name' => 'unique:crimes,name'],
                    ['name.unique' => "يجب ان يكون اسم الجريمة فريد"]
                );
            }
            $updataCrime->name = htmlspecialchars(strip_tags($data["name"]));
            if ($updataCrime->save()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل جريمة برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->performedOn($updataCrime)->event("تعديل جريمة")->causedBy($user)
                    ->log(
                        " تم تعديل الجريمة " . $updataCrime->name .
                            " معرف الجريمة " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Crime.index");
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
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
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
                    'id.required' => "معرف الجريمة مطلوب",
                    'id.integer' => "معرف الجريمة مطلوب",
                    'id.max' => "اكبر حد لمعرف الجريمة 255 حرف",
                    'id.min' => "اقل حد لمعرف الجريمة 1",
                ]
            );
            $rowsAffected = Crime::destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($rowsAffected) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم حذف جريمة برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->event("حذف جريمة")->causedBy($user)
                    ->log(
                        " تم حذف الجريمة " . $name .
                            " معرف الجريمة " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error('الجريمة غير موجودة ');
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفها لان هناك عمليات مرتبطة بهذه الجريمة");
            return redirect()->back();
        }
    }
}
