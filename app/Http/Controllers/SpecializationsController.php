<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
use App\Http\Requests\SpecializationsRequest;
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
                'educational_qualification',
                'lowest_acceptance_rate',
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

    public function getSpecializations(Request $request)
    {
        $collegeId = $request->input('college_id');
        $specializations = Specialization::where('college_id', $collegeId)->get();
        return response()->json($specializations);
    }

    public function create()
    {
    }
    public function store(SpecializationsRequest $request)
    {
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'registration' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $AddSpecialization = new Specialization();
            $AddSpecialization->name = htmlspecialchars(strip_tags($request["name"]));
            $AddSpecialization->Number_of_years_of_study = htmlspecialchars(strip_tags($request["Number_of_years_of_study"]));
            $AddSpecialization->Number_of_semester_of_study = htmlspecialchars(strip_tags($request["Number_of_years_of_study"])) * 2;
            $AddSpecialization->college_id = htmlspecialchars(strip_tags($request["college_id"]));
            $AddSpecialization->Price = htmlspecialchars(strip_tags($request["Price"]));
            $AddSpecialization->educational_qualification = htmlspecialchars(strip_tags($request["educational_qualification"]));
            $AddSpecialization->lowest_acceptance_rate = htmlspecialchars(strip_tags($request["lowest_acceptance_rate"]));
            if ($AddSpecialization->save()) {
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم اضافة تخصص باسم " . $request["name"] . " بواسطة الادمن" . $user->name
                    . " الوقت والتاريخ " . $date);
                activity()->performedOn($AddSpecialization)->event("اضافة تخصص")->causedBy($user)
                    ->log(
                        " تم اضافة تخصص جديد باسم " . $AddSpecialization->name .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Specialization.index");
            } else {
                toastr()->error('العملية فشلت');
                return redirect()->back();
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->with(["error" => $e->getMessage()])->withInput();
        }
    }

    public function edit(string $id)
    {
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'registration' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $Specializations = Specialization::select(
                'id',
                'name',
                'college_id',
                'Price',
                'Number_of_years_of_study',
                'educational_qualification',
                'lowest_acceptance_rate',
            )->where("id", $id)->first();

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
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'registration' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $updateSpecialization =  Specialization::find(htmlspecialchars(strip_tags($request["id"])));
            $updateSpecialization->name = htmlspecialchars(strip_tags($request["name"]));
            $updateSpecialization->Number_of_years_of_study = htmlspecialchars(strip_tags($request["Number_of_years_of_study"]));
            $updateSpecialization->Number_of_semester_of_study = htmlspecialchars(strip_tags($request["Number_of_years_of_study"])) * 2;
            $updateSpecialization->college_id = htmlspecialchars(strip_tags($request["college_id"]));
            $updateSpecialization->Price = htmlspecialchars(strip_tags($request["Price"]));
            $updateSpecialization->educational_qualification = htmlspecialchars(strip_tags($request["educational_qualification"]));
            $updateSpecialization->lowest_acceptance_rate = htmlspecialchars(strip_tags($request["lowest_acceptance_rate"]));
            if ($updateSpecialization->save()) {
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل تخصص باسم " . $request["name"] . " بواسطة الادمن" . $user->name
                    . " الوقت والتاريخ " . $date);
                activity()->performedOn($updateSpecialization)->event("اضافة تخصص")->causedBy($user)
                    ->log(
                        " تم تعديل تخصص باسم " . $updateSpecialization->name .
                            " بواسطة الادمن" . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Specialization.index");
            } else {
                toastr()->error('العملية فشلت');
                return redirect()->back();
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->back()->with(["error" => $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name)
    {
        try {
            $user = auth()->user();
            if ($user->user_type != 'admin' ) {
                toastr()->error("غير مصرح لك");
                return redirect()->back();
            }
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
