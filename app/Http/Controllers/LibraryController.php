<?php

namespace App\Http\Controllers;

use App\Models\library;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class LibraryController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        try {
            $library = Library::select('id', 'name','description','image','url')->get();
            if ($library->isEmpty()) {
                toastr()->error('لا يوجد كتب');
            }
            return view("page.library.index", [
                'data' => $library ?? []
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.library.index", [
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("page.library.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     [
            //         'name' => 'required|string|unique:librarys,name|min:2',
            //         'description' => 'required|string|unique:librarys,name|min:2',
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
            $Addlibrary = new library();
            $Addlibrary->name = htmlspecialchars(strip_tags($request["name"]));
            $Addlibrary->description = htmlspecialchars(strip_tags($request["description"]));
            if (isset($request["file"]) && !empty($request["file"])) {
                $AddlibraryImage = request()->file('file');
                $AddlibraryImagePath = 'library/Books/' .
                    $AddlibraryImage->getClientOriginalName();
                $AddlibraryImage->move(public_path('library/Books/'), $AddlibraryImagePath);
                $Addlibrary->image = $AddlibraryImagePath;
                $Addlibrary->url = $AddlibraryImagePath;
            }
            if ($Addlibrary->save()) {

                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');

                activity()->performedOn($Addlibrary)->event("اضافة كتاب")->causedBy($user)
                    ->log(
                        ' تم اضافة كتاب جديد باسم ' . $Addlibrary->name .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("library_Book.index");
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
            $library = library::find(htmlspecialchars(strip_tags($id)));
            return view("page.library.edit", [
                'library' => $library
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
                    'id.required' => "معرف المكتبة مطلوب",
                    'id.integer' => "معرف المكتبة مطلوب",
                    'id.max' => "اكبر حد لمعرف المكتبة 255 حرف",
                    'id.min' => "اقل حد لمعرف المكتبة 1",
                    'name.required' => "اسم المكتبة مطلوب",
                    'name.string' => "يجب ان يكون اسم المكتبة نص",
                    'name.max' => "اكبر حد لاسم المكتبة 255 حرف",
                    'name.min' => "اقل حد لاسم المكتبة 2",
                ]
            );

            $updatalibrary = library::findOrFail(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($request->has('name') && $data["name"] != $updatalibrary->name) {
                request()->validate(
                    ['name' => 'unique:librarys,name'],
                    ['name.unique' => "يجب ان يكون اسم المكتبة فريد"]
                );
            }
            $updatalibrary->name = htmlspecialchars(strip_tags($data["name"]));
            if ($updatalibrary->save()) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم تعديل كتاب برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->performedOn($updatalibrary)->event("تعديل كتاب")->causedBy($user)
                    ->log(
                        " تم تعديل المكتبة " . $updatalibrary->name .
                            " معرف المكتبة " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات
                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("library.index");
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
                    'id.required' => "معرف المكتبة مطلوب",
                    'id.integer' => "معرف المكتبة مطلوب",
                    'id.max' => "اكبر حد لمعرف المكتبة 255 حرف",
                    'id.min' => "اقل حد لمعرف المكتبة 1",
                ]
            );
            $rowsAffected = library::destroy(htmlspecialchars(strip_tags($data["id"]), ENT_QUOTES));
            if ($rowsAffected) {
                //اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');
                HelperController::NotificationsAdmin(" لقد تم حذف كتاب برقم " . $data["id"] . " بواسطة المستخدم " . $user->name
                    . " الوقت والتاريخ " . $date);

                activity()->event("حذف كتاب")->causedBy($user)
                    ->log(
                        " تم حذف المكتبة " . $name .
                            " معرف المكتبة " . $data["id"] .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
                //نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success("تم الحذف بنجاح");
                return redirect()->back();
            } else {
                toastr()->error('المكتبة غير موجودة ');
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لايمكنك حذفها لان هناك عمليات مرتبطة بهذه المكتبة");
            return redirect()->back();
        }
    }
}
