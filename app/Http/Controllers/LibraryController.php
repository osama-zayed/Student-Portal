<?php

namespace App\Http\Controllers;

use App\Models\library;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
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
            $library = Library::select('id', 'name', 'description', 'image', 'url')->get();
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
    public function store(BookRequest $request)
    {
        try {
            $Addlibrary = new library();
            $Addlibrary->name = htmlspecialchars(strip_tags($request["name"]));
            $Addlibrary->description = htmlspecialchars(strip_tags($request["description"]));
            if (isset($request["Image"]) && !empty($request["Image"])) {
                $AddlibraryImage = request()->file('Image');
                $AddlibraryImagePath = 'library/Books/' .
                    $AddlibraryImage->getClientOriginalName();
                $AddlibraryImage->move(public_path('library/Books/'), $AddlibraryImagePath);
                $Addlibrary->image = $AddlibraryImagePath;
            }
            if (isset($request["file"]) && !empty($request["file"])) {
                $AddlibraryFile = request()->file('file');
                $AddlibraryFilePath = 'library/Books/' .
                    $AddlibraryFile->getClientOriginalName();
                $AddlibraryFile->move(public_path('library/Books/'), $AddlibraryFilePath);
                $Addlibrary->url = $AddlibraryFilePath;
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
    public function update(BookRequest $request, string $id)
    {
        try {
            $updateLibrary = library::find(htmlspecialchars(strip_tags($request['id'])));
            $updateLibrary->name = htmlspecialchars(strip_tags($request["name"]));
            $updateLibrary->description = htmlspecialchars(strip_tags($request["description"]));
            if (isset($request["Image"]) && !empty($request["Image"])) {
                $updateLibraryImage = request()->file('Image');
                $updateLibraryImagePath = 'library/Books/' .
                    $updateLibraryImage->getClientOriginalName();
                $updateLibraryImage->move(public_path('library/Books/'), $updateLibraryImagePath);
                $updateLibrary->image = $updateLibraryImagePath;
            }
            if (isset($request["file"]) && !empty($request["file"])) {
                $updateLibraryFile = request()->file('file');
                $updateLibraryFilePath = 'library/Books/' .
                    $updateLibraryFile->getClientOriginalName();
                $updateLibraryFile->move(public_path('library/Books/'), $updateLibraryFilePath);
                $updateLibrary->url = $updateLibraryFilePath;
            }
            if ($updateLibrary->save()) {

                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                $date = date('H:i Y-m-d');

                activity()->performedOn($updateLibrary)->event("اضافة كتاب")->causedBy($user)
                    ->log(
                        ' تم تعديل كتاب باسم ' . $updateLibrary->name .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date,
                    );
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
