<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin'); 
    }
    public function index()
    {
        try {
            $Departments = Department::select('id', 'name', 'phone_number')->get();

            if ($Departments->isEmpty()) {
                toastr()->error('لا يوجد اقسام');
            }

            return view("page.Department.index", [
                'data' => $Departments
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Department.index");
        }
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
        try {
            //التحقق من الحقول
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|unique:Departments,name|min:2',
                    'phone_number' => 'required|numeric|digits:9|starts_with:7',

                ],
                [
                    'phone_number.required' => "رقم هاتف مركز الشرطة مطلوب",
                    'phone_number.numeric' => "يجب أن يكون رقم هاتف مركز الشرطة رقم",
                    'phone_number.digits' => "يجب أن يكون رقم هاتف مركز الشرطة 9 أرقام",
                    'phone_number.starts_with' => "يجب أن يبدأ رقم هاتف مركز الشرطة برقم 7",
                    'name.required' => 'حقل الاسم مطلوب',
                    'name.string' => "يجب ان يكون اسم مركز الشرطة نص",
                    'name.max' => "اكبر حد لاسم مركز الشرطة 255 حرف",
                    'name.min' => "اقل حد لاسم مركز الشرطة 2",
                    'name.unique' => "يجب ان يكون اسم مركز الشرطة فريد",
                ]
            );
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $AddDepartment = new Department();
            $AddDepartment->name = htmlspecialchars(strip_tags($request["name"]));
            $AddDepartment->phone_number = htmlspecialchars(strip_tags($request["phone_number"]));
            if ($AddDepartment->save()) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم اضافة مركز شرطة باسم " . $request["name"] . " بواسطة الادمن" . $user->name
                . " الوقت والتاريخ " . $date);
                activity()->performedOn($AddDepartment)->event("اضافة قسم")->causedBy($user)
                    ->log(
                        " تم اضافة قسم جديدة باسم " . $AddDepartment->project_name .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Department.index");
            } else {
                toastr()->error('العملية فشلت');
                return redirect()->back();
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {        
        try {
            $Departments = Department::select('id', 'name', 'phone_number')->where("id", $id)->first();

            return view("page.Department.edit", [
                'Departments' => $Departments,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }

    public function update(Request $request, string $id)
    {
        //التحقق من الحقول
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "id" => "required|integer|min:1",
                    'name' => 'string|min:2',
                    'phone_number' => 'required|numeric|digits:9|starts_with:7',

                ],
                [
                    'id.required' => "معرف مركز الشرطة مطلوب",
                    'id.integer' => "معرف مركز الشرطة مطلوب",
                    'id.max' => "اكبر حد لمعرف مركز الشرطة 255 حرف",
                    'id.min' => "اقل حد لمعرف مركز الشرطة 1",
                    'name.required' => 'حقل الاسم مطلوب',
                    'phone_number.required' => "رقم هاتف مركز الشرطة مطلوب",
                    'phone_number.numeric' => "يجب أن يكون رقم هاتف مركز الشرطة رقم",
                    'phone_number.digits' => "يجب أن يكون رقم هاتف مركز الشرطة 9 أرقام",
                    'phone_number.starts_with' => "يجب أن يبدأ رقم هاتف مركز الشرطة برقم 7",
                    'name.string' => "يجب ان يكون اسم مركز الشرطة نص",
                    'name.max' => "اكبر حد لاسم مركز الشرطة 255 حرف",
                    'name.min' => "اقل حد لاسم مركز الشرطة 2",
                ]
            );
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $updataDepartment = Department::findOrFail(htmlspecialchars(strip_tags($request->id)));
            if (request()->has('name') && $request->name != $updataDepartment->name) {
                request()->validate(
                    ['name' => 'unique:Departments,name'],
                    ['name.unique' => "يجب ان يكون اسم مركز الشرطة فريد"]
                );
                $updataDepartment->name = htmlspecialchars(strip_tags($request->name));
            }
            if (request()->has('phone_number'))
                $updataDepartment->phone_number = htmlspecialchars(strip_tags($request->phone_number));

            if ($updataDepartment->save()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل مركز الشرطة برقم " . $request["id"] . " بواسطة الادمن" . $user->name
                . " الوقت والتاريخ " . $date);
                activity()->performedOn($updataDepartment)->event("تعديل قسم")->causedBy($user)
                    ->log(
                        " تم تعديل قسم باسم " . $updataDepartment->project_name .
                            " معرف المحافظة " . $request->id .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Department.index");
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
                    'id.required' => "معرف مركز الشرطة مطلوب",
                    'id.integer' => "معرف مركز الشرطة مطلوب",
                    'id.max' => "اكبر حد لمعرف مركز الشرطة 255 حرف",
                    'id.min' => "اقل حد لمعرف مركز الشرطة 1",
                ]
            );
            $rowsAffected = Department::destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($rowsAffected) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم حذف مركز الشرطة برقم " . $data["id"] . " بواسطة الادمن" . $user->name
                . " الوقت والتاريخ " . $date);
                activity()->event("حذف مركز الشرطة")->causedBy($user)
                    ->log(
                        " تم تعديل مركز الشرطة " . $name .
                            " معرف مركز الشرطة " . $data["id"] .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error('مركز الشرطة غير موجودة ');
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفه لان هناك عمليات مرتبطة بهذه مركز الشرطة");
            return redirect()->back();
        }
    }
}
