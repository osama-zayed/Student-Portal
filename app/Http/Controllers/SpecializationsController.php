<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class SpecializationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index()
    {
        try {
            $Specializations = Specialization::select(
                'id',
                'name',
                'college_id',
                'Price',
                'Number_of_years_of_study',
            )->get();

            if ($Specializations->isEmpty()) {
                toastr()->error('لا يوجد تخصصات');
            }

            return view("page.Specialization.index", [
                'data' => $Specializations
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Specialization.index");
        }
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
        try {
            //التحقق من الحقول
            $validator = Validator::make($request->all(), [
                    'name' => 'required|string|unique:specializations,name|min:2',
                    'Number_of_years_of_study' => 'required|integer|min:4|max:6',
                    'Price' => 'required|integer|min:500|max:6000',
                    'college_id' => 'required|integer|min:1',
                ],
                [
                    'Number_of_years_of_study.required' => "عدد السنين الدراسية مطلوب",
                    'Number_of_years_of_study.integer' => "يجب أن يكون عدد السنين الدراسية رقم",
                    'Number_of_years_of_study.min' => "يجب أن يكون عدد السنين الدراسية اكبر من 3",
                    'Number_of_years_of_study.max' => "يجب أن يكون عدد السنين الدراسية اقل من 6",
                    'college_id.required' => "رقم الكلية مطلوب",
                    'college_id.integer' => "يجب أن يكون رقم الكلية رقم",
                    'college_id.min' => "يجب أن يكون رقم الكلية اكبر من 0",
                    'college_id.max' => "يجب أن يكون رقم الكلية اقل من 100",
                    'Price.required' => "السعر مطلوب",
                    'Price.integer' => "يجب أن يكون السعر رقم",
                    'Price.min' => "يجب أن يكون السعر اكبر من 500$",
                    'Price.max' => "يجب أن يكون السعر اقل من 6000$",
                    'name.required' => 'حقل الاسم مطلوب',
                    'name.string' => "يجب ان يكون اسم التخصص نص",
                    'name.max' => "اكبر حد لاسم التخصص 255 حرف",
                    'name.min' => "اقل حد لاسم التخصص 2",
                    'name.unique' => "يجب ان يكون اسم التخصص فريد",
                ]
            );
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $AddSpecialization = new Specialization();
            $AddSpecialization->name = htmlspecialchars(strip_tags($request["name"]));
            $AddSpecialization->Number_of_years_of_study = htmlspecialchars(strip_tags($request["Number_of_years_of_study"]));
            $AddSpecialization->college_id = htmlspecialchars(strip_tags($request["college_id"]));
            $AddSpecialization->Price = htmlspecialchars(strip_tags($request["Price"]));
            if ($AddSpecialization->save()) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم اضافة تخصص باسم " . $request["name"] . " بواسطة الادمن" . $user->name
                    . " الوقت والتاريخ " . $date);
                activity()->performedOn($AddSpecialization)->event("اضافة تخصص")->causedBy($user)
                    ->log(
                        " تم اضافة تخصص جديد باسم " . $AddSpecialization->name .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Specialization.index");
            } else {
                toastr()->error('العملية فشلت');
                return redirect()->back();
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->with(["error" => $e->getMessage()]) ->withInput();
        }
    }

    public function edit(string $id)
    {
        try {
            $Specializations = Specialization::select('id', 'name', 'Number_of_years_of_study')->where("id", $id)->first();

            return view("page.Specialization.edit", [
                'Specializations' => $Specializations,
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
                    'Number_of_years_of_study' => 'required|integer|digits:9|starts_with:7',

                ],
                [
                    'id.required' => "معرف التخصص مطلوب",
                    'id.integer' => "معرف التخصص مطلوب",
                    'id.max' => "اكبر حد لمعرف التخصص 255 حرف",
                    'id.min' => "اقل حد لمعرف التخصص 1",
                    'name.required' => 'حقل الاسم مطلوب',
                    'Number_of_years_of_study.required' => "عدد السنين الدراسية مطلوب",
                    'Number_of_years_of_study.integer' => "يجب أن يكون عدد السنين الدراسية رقم",
                    'Number_of_years_of_study.digits' => "يجب أن يكون عدد السنين الدراسية 9 أرقام",
                    'Number_of_years_of_study.starts_with' => "يجب أن يبدأ عدد السنين الدراسية برقم 7",
                    'name.string' => "يجب ان يكون اسم التخصص نص",
                    'name.max' => "اكبر حد لاسم التخصص 255 حرف",
                    'name.min' => "اقل حد لاسم التخصص 2",
                ]
            );
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $updataSpecialization = Specialization::findOrFail(htmlspecialchars(strip_tags($request->id)));
            if (request()->has('name') && $request->name != $updataSpecialization->name) {
                request()->validate(
                    ['name' => 'unique:Specializations,name'],
                    ['name.unique' => "يجب ان يكون اسم التخصص فريد"]
                );
                $updataSpecialization->name = htmlspecialchars(strip_tags($request->name));
            }
            if (request()->has('Number_of_years_of_study'))
                $updataSpecialization->Number_of_years_of_study = htmlspecialchars(strip_tags($request->Number_of_years_of_study));

            if ($updataSpecialization->save()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل التخصص برقم " . $request["id"] . " بواسطة الادمن" . $user->name
                    . " الوقت والتاريخ " . $date);
                activity()->performedOn($updataSpecialization)->event("تعديل تخصص")->causedBy($user)
                    ->log(
                        " تم تعديل تخصص باسم " . $updataSpecialization->project_name .
                            " معرف المحافظة " . $request->id .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Specialization.index");
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
                    'id.required' => "معرف التخصص مطلوب",
                    'id.integer' => "معرف التخصص مطلوب",
                    'id.max' => "اكبر حد لمعرف التخصص 255 حرف",
                    'id.min' => "اقل حد لمعرف التخصص 1",
                ]
            );
            $rowsAffected = Specialization::destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($rowsAffected) {

                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم حذف التخصص برقم " . $data["id"] . " بواسطة الادمن" . $user->name
                    . " الوقت والتاريخ " . $date);
                activity()->event("حذف التخصص")->causedBy($user)
                    ->log(
                        " تم تعديل التخصص " . $name .
                            " معرف التخصص " . $data["id"] .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error('مركز التخصص غير موجودة ');
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفه لان هناك عمليات مرتبطة بهذه التخصص");
            return redirect()->back();
        }
    }
}
