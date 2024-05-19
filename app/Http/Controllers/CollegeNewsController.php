<?php

namespace App\Http\Controllers;

use App\Models\CollegeNew;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            )->get();
            if ($CollegeNew->isEmpty()) {
                toastr()->error('لا يوجد اخبار');
            }
            return view("page.CollegeNew.index", [
                'data' => $CollegeNew??[]
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
        return view("page.CollegeNew.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     [
            //         'name' => 'required|string|unique:CollegeNews,name|min:2',
            //         'description' => 'required|string|unique:CollegeNews,name|min:2',
            //     ],
            //     [
            //         'name.required' => "اسم المكتبة مطلوب",
            //         'name.string' => "يجب ان يكون اسم المكتبة نص",
            //         'name.max' => "اكبر حد لاسم المكتبة 255 حرف",
            //         'name.min' => "اقل حد لاسم المكتبة 2",
            //         'name.unique' => "يجب ان يكون اسم المكتبة فريد",
            //     ]
            // ]);
            // if ($validator->fails()) {
            //     toastr()->error($validator->errors()->first());
            //     return redirect()->back()
            //         ->withErrors($validator)
            //         ->withInput();
            // }
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
                        ' تم اضافة خبر جديد باسم ' . $AddCollegeNew->name .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("CollegeNew_Book.index");
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

            $updataCollegeNew = CollegeNew::findOrFail(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($request->has('name') && $data["name"] != $updataCollegeNew->name) {
                request()->validate(
                    ['name' => 'unique:CollegeNews,name'],
                    ['name.unique' => "يجب ان يكون اسم الكلية فريد"]
                );
            }
            $updataCollegeNew->name = htmlspecialchars(strip_tags($data["name"]));
            if ($updataCollegeNew->save()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل كلية برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->performedOn($updataCollegeNew)->event("تعديل كلية")->causedBy($user)
                    ->log(
                        " تم تعديل الكلية " . $updataCollegeNew->name .
                            " معرف الكلية " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("CollegeNew.index");
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
