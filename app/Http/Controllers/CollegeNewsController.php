<?php

namespace App\Http\Controllers;

use App\Models\CollegeNew;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewRequest;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class CollegeNewsController extends Controller
{
    public function index()
    {
        try {
            $CollegeNew = CollegeNew::select(
                'id',
                'title',
                'image',
                "description",
            )->orderBy('id', 'desc')
            ->get();
            if ($CollegeNew->isEmpty()) {
                toastr()->error('لا يوجد اخبار');
            }
            return view("page.CollegeNew.index", [
                'data' => $CollegeNew ?? []
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.CollegeNew.index", [
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->user_type == 'control' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        return view("page.CollegeNew.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewRequest $request)
    {
        $user = auth()->user();
        if ($user->user_type == 'control' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $AddCollegeNew = new CollegeNew();
            $AddCollegeNew->title = htmlspecialchars(strip_tags($request["title"]));
            $AddCollegeNew->description = htmlspecialchars(strip_tags($request["description"]));
            if (isset($request["file"]) && !empty($request["file"])) {
                $AddCollegeNewImage = request()->file('file');
                $AddCollegeNewImagePath = 'CollegeNew/' . 
                    $AddCollegeNewImage->getClientOriginalName();
                $AddCollegeNewImage->move(public_path('CollegeNew/'), $AddCollegeNewImagePath);
                $AddCollegeNew->image = $AddCollegeNewImagePath;
            }

            if ($AddCollegeNew->save()) {

                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');

                HelperController::NotificationsAllUser("لقد تمت إضافة خبر جديد");

                activity()->performedOn($AddCollegeNew)->event("اضافة خبر")->causedBy($user)
                    ->log(
                        ' تم اضافة خبر جديد بعنوان ' . $AddCollegeNew->name .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("College-New.index");
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
        $user = auth()->user();
        if ($user->user_type == 'control' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $CollegeNew = CollegeNew::find(htmlspecialchars(strip_tags($id)));
            return view("page.CollegeNew.edit", [
                'CollegeNew' => $CollegeNew
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewRequest $request, string $id)
    {
        $user = auth()->user();
        if ($user->user_type == 'control' ) {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $updateCollegeNew = CollegeNew::find(htmlspecialchars(strip_tags($request["id"])));
            $updateCollegeNew->title = htmlspecialchars(strip_tags($request["title"]));
            $updateCollegeNew->description = htmlspecialchars(strip_tags($request["description"]));
            if (isset($request["file"]) && !empty($request["file"])) {
                $updateCollegeNewImage = request()->file('file');
                $updateCollegeNewImagePath = 'CollegeNew/' .
                    $updateCollegeNewImage->getClientOriginalName();
                $updateCollegeNewImage->move(public_path('CollegeNew/'), $updateCollegeNewImagePath);
                $updateCollegeNew->image = $updateCollegeNewImagePath;
            }

            if ($updateCollegeNew->save()) {

                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');

                HelperController::NotificationsAllUser("لقد تمت إضافة خبر جديد");

                activity()->performedOn($updateCollegeNew)->event("تعديل خبر")->causedBy($user)
                    ->log(
                        ' تم تعديل خبر بعنوان ' . $updateCollegeNew->name .
                            " بواسطة المستخدم " . $user->title .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("College-New.index");
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
        $user = auth()->user();
        if ($user->user_type == 'control' ) {
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
                    'id.required' => "معرف الكلية مطلوب",
                    'id.integer' => "معرف الكلية مطلوب",
                    'id.max' => "اكبر حد لمعرف الكلية 255 حرف",
                    'id.min' => "اقل حد لمعرف الكلية 1",
                ]
            );
            $rowsAffected = CollegeNew::destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
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
