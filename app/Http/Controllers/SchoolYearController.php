<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolYearRequest;
use App\Models\SchoolYear;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $SchoolYear = SchoolYear::select(
                'id',
                'name',
                'start_date',
                'end_date',
                'is_current',
                'UniversityCalendar',
            )->get();

            if ($SchoolYear->isEmpty()) {
                toastr()->error('لا يوجد اعوام دراسية');
            }
            return view("page.SchoolYear.index", [
                'data' => $SchoolYear,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Course.index", [
                'data' => [],
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SchoolYearRequest $request)
    {
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'registration' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $latestSchoolYear = SchoolYear::latest()->first();

            if ($latestSchoolYear) {
                $latestStartDate = Carbon::parse($latestSchoolYear->start_date);
                $newStartDate = Carbon::parse($request['start_date'] . '-01');

                $monthsDifference = $newStartDate->diffInMonths($latestStartDate);

                if ($monthsDifference < 8) {
                    toastr()->error('يجب أن يكون الفرق بين تاريخ البداية للسنة دراسة الحالية وتاريخ البداية لآخر سنة دراسة 8 أشهر على الأقل.');
                    return redirect()->back()->withInput();
                }

                // تعطيل العام الدراسي السابق
                $latestSchoolYear->update(['is_current' => false]);
            }

            // إنشاء العام الدراسي الجديد
            $newSchoolYear = SchoolYear::create([
                'name' => $request['name'],
                'start_date' => $request['start_date'] . '-01',
                'end_date' => $request['end_date'] . '-01',
                'is_current' => true,
            ]);

            toastr()->success('تمت العملية بنجاح');
        } catch (\Throwable $th) {
            toastr()->error('العملية فشلت');
        }

        return redirect()->route('SchoolYear.index');
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
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'registration' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            
            $SchoolYear = SchoolYear::find(htmlspecialchars(strip_tags($id)));
            if (!$SchoolYear->is_current) {
                toastr()->error('لا يمكن تعديل العام الدراسي الذي انتهى.');
                return redirect()->back();
            }
            return view("page.SchoolYear.edit", [
                'SchoolYear' => $SchoolYear
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolYearRequest $request, string $id)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name)
    {
        try {
            $user = auth()->user();
            if ($user->user_type != 'admin') {
                toastr()->error("غير مصرح لك");
                return redirect()->back();
            }
            $data = request()->validate(
                [
                    "id" => "required|integer|min:1",
                ],
                [
                    'id.required' => "معرف السنة الدراسية مطلوب",
                    'id.integer' => "معرف السنة الدراسية مطلوب",
                    'id.max' => "اكبر حد لمعرف السنة الدراسية 255 حرف",
                    'id.min' => "اقل حد لمعرف السنة الدراسية 1",
                ]
            );
            $SchoolYear = SchoolYear::find(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if (!$SchoolYear->is_current) {
                toastr()->error('لا يمكن حذف العام الدراسي الذي انتهى.');
                return redirect()->back();
            }
            $rowsAffected =  $SchoolYear->destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($rowsAffected) {
                $latestSchoolYear = SchoolYear::latest()->first();
                $latestSchoolYear->update(['is_current' => true]);
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم حذف كتاب برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->event("حذف كتاب")->causedBy($user)
                    ->log(
                        " تم حذف السنة الدراسية " . $name .
                            " معرف السنة الدراسية " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error('السنة الدراسية غير موجودة');
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفها لان هناك عمليات مرتبطة بهذه السنة الدراسية");
            return redirect()->back();
        }
    }
}
