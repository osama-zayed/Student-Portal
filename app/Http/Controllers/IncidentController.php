<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Notifications\Notifications;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            $incident_status = request()->input('incident_status');
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $query = Incident::query();
            if ($incident_status) {
                $title = $incident_status;
                $query->where('incident_status', $incident_status);
            }
            if (auth()->user()->user_type == 'user') {
                $query->where('department_id', auth()->user()->department_id);
            }
            $totalData = $query->count();
            $totalPages = ceil($totalData / $pageSize);
            $Incident = $query->skip($skip)->take($pageSize)->with('department', 'crimeType')->where('main_incident_id', null)->get();
            if ($Incident->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Incident.index", [
                'data' => $Incident,
                "page" => $page,
                "title" => $title ?? 'قائمة البلاغات',
                'edit'=>true,
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Incident.index", [
                "totalPages" => $totalPages,
                "title" => $title ?? 'قائمة البلاغات',
                'edit'=>true,
                "page" => $page,
            ]);
        }
    }

    public function showDeleted()
    {
        if (auth()->user()->user_type == 'user') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $query = Incident::query();
            $totalData = $query->onlyTrashed()->count();
            $totalPages = ceil($totalData / $pageSize);
            $Incident = $query->onlyTrashed()->skip($skip)->take($pageSize)->with('department', 'crimeType')->get();
            if ($Incident->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.Incident.index", [
                'data' => $Incident,
                "page" => $page,
                "title" => 'البلاغات المؤرشفة',
                'edit'=>false,
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Incident.index", [
                "totalPages" => $totalPages,
                "title" => 'البلاغات المؤرشفة',
                'edit'=>false,
                "page" => $page,
            ]);
        }
    }

    public function create()
    {
        return view("page.Incident.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            // التحقق من الحقول
            $validator = Validator::make($request->all(), [
                'incident_number' => 'required|integer|min:1|unique:incidents,incident_number',
                'crime_type_id' => 'required|integer',
                'incident_date' => 'required|date',
                'department_id' => 'required|integer',
                'incident_time' => 'required',
                'date_occurred' => 'required|date',
                'incident_location' => 'required|string|min:2',
                'reasons_and_motives' => 'required',
                'tools_used' => 'required',
                'number_of_victims' => 'required|integer|min:0',
                'number_of_perpetrators' => 'required|integer|min:0',
                'incident_status' => 'required|string|min:2',
                'incident_description' => 'required',
                'incident_image' => 'nullable|image',
                'notes' => 'nullable',
            ], [
                'incident_number.required' => 'حقل رقم البلاغ مطلوب',
                'incident_number.string' => 'حقل رقم البلاغ يجب أن يكون نصًا',
                'incident_number.min' => 'حقل رقم البلاغ يجب أن يتكون من الحد الأدنى للحروف',
                'incident_number.unique' => 'يجب ان يكون رقم البلاغ فريد',
                'crime_type_id.required' => 'حقل نوع الجريمة مطلوب',
                'crime_type_id.integer' => 'حقل نوع الجريمة يجب أن يكون رقمًا صحيحًا',
                'incident_date.required' => 'حقل تاريخ البلاغ مطلوب',
                'incident_date.date' => 'حقل تاريخ البلاغ يجب أن يكون تاريخًا',
                'department_id.required' => 'حقل القسم مطلوب',
                'department_id.integer' => 'حقل القسم يجب أن يكون رقمًا صحيحًا',
                'incident_time.required' => 'حقل زمن البلاغ مطلوب',
                'incident_location.required' => 'حقل مكان البلاغ مطلوب',
                'incident_location.string' => 'حقل مكان البلاغ يجب أن يكون نصًا',
                'incident_location.min' => 'حقل مكان البلاغ يجب أن يتكون من الحد الأدنى للحروف',
                'reasons_and_motives.required' => 'حقل الأسباب والدوافع مطلوب',
                'tools_used.required' => 'حقل الأدوات المستخدمة مطلوب',
                'number_of_perpetrators.required' => 'حقل عدد الضحايا مطلوب',
                'number_of_perpetrators.integer' => 'حقل عدد الضحايا يجب أن يكون رقمًا صحيحًا',
                'number_of_perpetrators.min' => 'حقل عدد الضحايا يجب أن يكون أكبر من الصفر',
                'number_of_victims.required' => 'حقل عدد الجناة مطلوب',
                'number_of_victims.integer' => 'حقل عدد الجناة يجب أن يكون رقمًا صحيحًا',
                'number_of_victims.min' => 'حقل عدد الجناة يجب أن يكون أكبر من الصفر',
                'incident_status.required' => 'حقل حالة البلاغ مطلوب',
                'incident_status.string' => 'حقل حالة البلاغ يجب أن يكون نصًا',
                "incident_description.required" => "حقل شرح البلاغ مطلوب",
                "incident_image.required" => "حقل صوره البلاغ مطلوب",
                "incident_image.image" => "حقل صوره البلاغ ليست صوره",
            ]);
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $AddIncident = new  Incident();
            $AddIncident->incident_number = htmlspecialchars(strip_tags($request['incident_number']));
            $AddIncident->crime_type_id = htmlspecialchars(strip_tags($request['crime_type_id']));
            $AddIncident->incident_date = htmlspecialchars(strip_tags($request['incident_date']));
            $departmentId = htmlspecialchars(strip_tags($request['department_id']));
            $AddIncident->department_id = $departmentId;
            $AddIncident->incident_time = htmlspecialchars(strip_tags($request['incident_time']));
            $AddIncident->date_occurred = htmlspecialchars(strip_tags($request['date_occurred']));
            $AddIncident->incident_location = htmlspecialchars(strip_tags($request['incident_location']));
            $AddIncident->reasons_and_motives = htmlspecialchars(strip_tags($request['reasons_and_motives']));
            $AddIncident->tools_used = htmlspecialchars(strip_tags($request['tools_used']));
            $AddIncident->number_of_victims = htmlspecialchars(strip_tags($request['number_of_victims']));
            $AddIncident->number_of_perpetrators = htmlspecialchars(strip_tags($request['number_of_perpetrators']));
            $AddIncident->incident_status = htmlspecialchars(strip_tags($request['incident_status']));
            $AddIncident->incident_description = htmlspecialchars(strip_tags($request['incident_description']));
            $AddIncident->notes = htmlspecialchars(strip_tags($request['notes']));

            if (isset($request["incident_image"]) && !empty($request["incident_image"])) {
                $incidentImage = request()->file('incident_image');
                $incidentImagePath = 'images/incident_image/' .
                    $AddIncident->incident_number .  $incidentImage->getClientOriginalName();
                $incidentImage->move(public_path('images/incident_image/'), $incidentImagePath);
                $AddIncident->incident_image = $incidentImagePath;
            }
            if ($AddIncident->save()) {
                $date = date('H:i Y-m-d');
                $user = auth()->user();
                HelperController::NotificationsUserDepartment("لقد تمت إضافة بلاغ جديد برقم " . $AddIncident->incident_number . " نوع الجريمة " . $AddIncident->crime_type_id . " في تاريخ " . $date, $departmentId);

                activity()->performedOn($AddIncident)->event("إضافة بلاغ جديد")->causedBy($user)
                    ->log(
                        "تمت إضافة بلاغ جديد برقم " . $AddIncident->incident_number . " نوع الجريمة " . $AddIncident->crime_type_id . " في تاريخ " . $date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );

                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Incident.index");
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
    public function show($Incident)
    {
        try {
            $pageSize = 100;
            $page = request()->input('page', 1);
            $incident_status = request()->input('incident_status');
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $query = Incident::query();
            if ($incident_status) {
                $title = $incident_status;
                $query->where('incident_status', $incident_status);
            }
            if (auth()->user()->user_type == 'user') {
                $query->where('department_id', auth()->user()->department_id);
            }
            $totalData = $query->count();
            $totalPages = ceil($totalData / $pageSize);
            $Incident = $query->skip($skip)->take($pageSize)->with('department', 'crimeType')->where('main_incident_id', $Incident)->orWhere('id', $Incident)->get();
            if ($Incident->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
                return redirect()->back();
            }

            return view("page.Incident.index", [
                'data' => $Incident,
                "page" => $page,
                "title" => $title ?? 'البلاغ رقم '.$Incident[0]->incident_number,
                'edit'=>false,
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.Incident.index", [
                "totalPages" => $totalPages,
                "title" => $title ?? 'قائمة البلاغات',
                'edit'=>false,
                "page" => $page,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $Incident = Incident::select(
                'id',
                'incident_number',
                'crime_type_id',
                'incident_date',
                'department_id',
                'incident_time',
                'date_occurred',
                'incident_location',
                'reasons_and_motives',
                'tools_used',
                'number_of_victims',
                'number_of_perpetrators',
                'incident_status',
                'incident_description',
                'incident_image',
                'notes',
            )->where('id', $id)
                ->first();
            if (!$Incident) {
                toastr()->error("البلاغ غير موجود");
                return redirect()->back()
                    ->withInput();
            }
            return view("page.Incident.edit")->with("data", $Incident);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
    //         toastr()->error("غير مصرح لك");
    //         return redirect()->back();
    //     }
    //     try {
    //         // التحقق من الحقول
    //         $validator = Validator::make($request->all(), [
    //             'incident_number' => 'required|integer|min:1',
    //             'crime_type_id' => 'required|integer',
    //             'incident_date' => 'required|date',
    //             'department_id' => 'required|integer',
    //             'incident_time' => 'required',
    //             'date_occurred' => 'required|date',
    //             'incident_location' => 'required|string|min:2',
    //             'reasons_and_motives' => 'required',
    //             'tools_used' => 'required',
    //             'number_of_victims' => 'required|integer|min:0',
    //             'number_of_perpetrators' => 'required|integer|min:0',
    //             'incident_status' => 'required|string|min:2',
    //             'incident_description' => 'required',
    //             'incident_image' => 'nullable|image',
    //             'notes' => 'nullable',
    //         ], [
    //             'incident_number.required' => 'حقل رقم البلاغ مطلوب',
    //             'incident_number.string' => 'حقل رقم البلاغ يجب أن يكون نصًا',
    //             'incident_number.min' => 'حقل رقم البلاغ يجب أن يتكون من الحد الأدنى للحروف',
    //             'crime_type_id.required' => 'حقل نوع الجريمة مطلوب',
    //             'crime_type_id.integer' => 'حقل نوع الجريمة يجب أن يكون رقمًا صحيحًا',
    //             'incident_date.required' => 'حقل تاريخ البلاغ مطلوب',
    //             'incident_date.date' => 'حقل تاريخ البلاغ يجب أن يكون تاريخًا',
    //             'department_id.required' => 'حقل القسم مطلوب',
    //             'department_id.integer' => 'حقل القسم يجب أن يكون رقمًا صحيحًا',
    //             'incident_time.required' => 'حقل زمن البلاغ مطلوب',
    //             'incident_location.required' => 'حقل مكان البلاغ مطلوب',
    //             'incident_location.string' => 'حقل مكان البلاغ يجب أن يكون نصًا',
    //             'incident_location.min' => 'حقل مكان البلاغ يجب أن يتكون من الحد الأدنى للحروف',
    //             'reasons_and_motives.required' => 'حقل الأسباب والدوافع مطلوب',
    //             'tools_used.required' => 'حقل الأدوات المستخدمة مطلوب',
    //             'number_of_perpetrators.required' => 'حقل عدد الضحايا مطلوب',
    //             'number_of_perpetrators.integer' => 'حقل عدد الضحايا يجب أن يكون رقمًا صحيحًا',
    //             'number_of_perpetrators.min' => 'حقل عدد الضحايا يجب أن يكون أكبر من الصفر',
    //             'number_of_victims.required' => 'حقل عدد الجناة مطلوب',
    //             'number_of_victims.integer' => 'حقل عدد الجناة يجب أن يكون رقمًا صحيحًا',
    //             'number_of_victims.min' => 'حقل عدد الجناة يجب أن يكون أكبر من الصفر',
    //             'incident_status.required' => 'حقل حالة البلاغ مطلوب',
    //             'incident_status.string' => 'حقل حالة البلاغ يجب أن يكون نصًا',
    //             "incident_description.required" => "حقل شرح البلاغ مطلوب",
    //             "incident_image.required" => "حقل صوره البلاغ مطلوب",
    //             "incident_image.image" => "حقل صوره البلاغ ليست صوره",
    //         ]);
    //         $AddIncident = Incident::find(htmlspecialchars(strip_tags($request['id'])));
    //         if (!$AddIncident) {
    //             toastr()->error("البلاغ غير موجود");
    //             return redirect()->back()
    //                 ->withInput();
    //         }
    //         if (isset($request["incident_number"]) && !empty($request["incident_number"])  && request()->has('incident_number') && $request->incident_number != $AddIncident->incident_number) {
    //             $validator = Validator::make(
    //                 $request->all(),
    //                 ['incident_number' => 'unique:incidents,incident_number'],
    //                 [
    //                     'incident_number.unique' => 'رقم القيد يجب ان يكون فريد',
    //                 ]
    //             );
    //             $AddIncident->incident_number = htmlspecialchars(strip_tags($request->incident_number));
    //         }
    //         if ($validator->fails()) {
    //             toastr()->error($validator->errors()->first());
    //             return redirect()->back()
    //                 ->withErrors($validator)
    //                 ->withInput();
    //         }



    //         $AddIncident->incident_number = htmlspecialchars(strip_tags($request['incident_number']));
    //         $AddIncident->crime_type_id = htmlspecialchars(strip_tags($request['crime_type_id']));
    //         $AddIncident->incident_date = htmlspecialchars(strip_tags($request['incident_date']));
    //         $departmentId = htmlspecialchars(strip_tags($request['department_id']));
    //         $AddIncident->department_id = $departmentId;
    //         $AddIncident->incident_time = htmlspecialchars(strip_tags($request['incident_time']));
    //         $AddIncident->date_occurred = htmlspecialchars(strip_tags($request['date_occurred']));
    //         $AddIncident->incident_location = htmlspecialchars(strip_tags($request['incident_location']));
    //         $AddIncident->reasons_and_motives = htmlspecialchars(strip_tags($request['reasons_and_motives']));
    //         $AddIncident->tools_used = htmlspecialchars(strip_tags($request['tools_used']));
    //         $AddIncident->number_of_victims = htmlspecialchars(strip_tags($request['number_of_victims']));
    //         $AddIncident->number_of_perpetrators = htmlspecialchars(strip_tags($request['number_of_perpetrators']));
    //         $AddIncident->incident_status = htmlspecialchars(strip_tags($request['incident_status']));
    //         $AddIncident->incident_description = htmlspecialchars(strip_tags($request['incident_description']));
    //         $AddIncident->notes = htmlspecialchars(strip_tags($request['notes']));

    //         if (isset($request["incident_image"]) && !empty($request["incident_image"])) {
    //             $incidentImage = request()->file('incident_image');
    //             $incidentImagePath = 'images/incident_image/' .
    //                 $AddIncident->incident_number .  $incidentImage->getClientOriginalName();
    //             $incidentImage->move(public_path('images/incident_image/'), $incidentImagePath);
    //             $AddIncident->incident_image = $incidentImagePath;
    //         }
    //         if ($AddIncident->save()) {
    //             $user = User::find(auth()->user()->id);
    //             $date = date('H:i Y-m-d');
    //             HelperController::NotificationsUserDepartment("لقد تمت تعديل بلاغ برقم " . $AddIncident->incident_number . " نوع الجريمة " . $AddIncident->crime_type_id . " في تاريخ " . $date, $departmentId);

    //             activity()->performedOn($AddIncident)->event("تعديل بلاغ")->causedBy($user)
    //                 ->log(
    //                     "تم تعديل بلاغ برقم " . $AddIncident->incident_number . " نوع الجريمة " . $AddIncident->crime_type_id . " في تاريخ " . $date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
    //                 );

    //             toastr()->success('تمت العملية بنجاح');
    //             return redirect()->route("Incident.index");
    //         } else {
    //             toastr()->error('العملية فشلت');
    //             return redirect()->back();
    //         }
    //     } catch (Exception $e) {
    //         toastr()->error($e->getMessage());
    //         return redirect()->back()->with(["error" => $e->getMessage()]);
    //     }
    // }
    public function update(Request $request, string $id)
    {
        if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            // التحقق من الحقول
            $validator = Validator::make($request->all(), [
                'incident_number' => 'required|integer|min:1',
                'crime_type_id' => 'required|integer',
                'incident_date' => 'required|date',
                'department_id' => 'required|integer',
                'incident_time' => 'required',
                'date_occurred' => 'required|date',
                'incident_location' => 'required|string|min:2',
                'reasons_and_motives' => 'required',
                'tools_used' => 'required',
                'number_of_victims' => 'required|integer|min:0',
                'number_of_perpetrators' => 'required|integer|min:0',
                'incident_status' => 'required|string|min:2',
                'incident_description' => 'required',
                'incident_image' => 'nullable|image',
                'notes' => 'nullable',
            ], [
                'incident_number.required' => 'حقل رقم البلاغ مطلوب',
                'incident_number.string' => 'حقل رقم البلاغ يجب أن يكون نصًا',
                'incident_number.min' => 'حقل رقم البلاغ يجب أن يتكون من الحد الأدنى للحروف',
                'crime_type_id.required' => 'حقل نوع الجريمة مطلوب',
                'crime_type_id.integer' => 'حقل نوع الجريمة يجب أن يكون رقمًا صحيحًا',
                'incident_date.required' => 'حقل تاريخ البلاغ مطلوب',
                'incident_date.date' => 'حقل تاريخ البلاغ يجب أن يكون تاريخًا',
                'department_id.required' => 'حقل القسم مطلوب',
                'department_id.integer' => 'حقل القسم يجب أن يكون رقمًا صحيحًا',
                'incident_time.required' => 'حقل زمن البلاغ مطلوب',
                'incident_location.required' => 'حقل مكان البلاغ مطلوب',
                'incident_location.string' => 'حقل مكان البلاغ يجب أن يكون نصًا',
                'incident_location.min' => 'حقل مكان البلاغ يجب أن يتكون من الحد الأدنى للحروف',
                'reasons_and_motives.required' => 'حقل الأسباب والدوافع مطلوب',
                'tools_used.required' => 'حقل الأدوات المستخدمة مطلوب',
                'number_of_perpetrators.required' => 'حقل عدد الضحايا مطلوب',
                'number_of_perpetrators.integer' => 'حقل عدد الضحايا يجب أن يكون رقمًا صحيحًا',
                'number_of_perpetrators.min' => 'حقل عدد الضحايا يجب أن يكون أكبر من الصفر',
                'number_of_victims.required' => 'حقل عدد الجناة مطلوب',
                'number_of_victims.integer' => 'حقل عدد الجناة يجب أن يكون رقمًا صحيحًا',
                'number_of_victims.min' => 'حقل عدد الجناة يجب أن يكون أكبر من الصفر',
                'incident_status.required' => 'حقل حالة البلاغ مطلوب',
                'incident_status.string' => 'حقل حالة البلاغ يجب أن يكون نصًا',
                "incident_description.required" => "حقل شرح البلاغ مطلوب",
                "incident_image.required" => "حقل صوره البلاغ مطلوب",
                "incident_image.image" => "حقل صوره البلاغ ليست صوره",
            ]);
            $updateIncident = Incident::find(htmlspecialchars(strip_tags($request['id'])));
            if (!$updateIncident) {
                toastr()->error("البلاغ غير موجود");
                return redirect()->back()->withInput();
            }

            if ($request->incident_status != $updateIncident->incident_status) {
                $AddIncident = new Incident();
                $AddIncident->fill($updateIncident->toArray());
                $AddIncident->id = null;
                $AddIncident->main_incident_id = $updateIncident->id;
            }
            if (isset($request["incident_number"]) && !empty($request["incident_number"])  && request()->has('incident_number') && $request->incident_number != $updateIncident->incident_number) {
                $validator = Validator::make(
                    $request->all(),
                    ['incident_number' => 'unique:incidents,incident_number'],
                    [
                        'incident_number.unique' => 'رقم القيد يجب ان يكون فريد',
                    ]
                );
                $updateIncident->incident_number = htmlspecialchars(strip_tags($request->incident_number));
            }
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            $updateIncident->incident_number = htmlspecialchars(strip_tags($request['incident_number']));
            $updateIncident->crime_type_id = htmlspecialchars(strip_tags($request['crime_type_id']));
            $updateIncident->incident_date = htmlspecialchars(strip_tags($request['incident_date']));
            $departmentId = htmlspecialchars(strip_tags($request['department_id']));
            $updateIncident->department_id = $departmentId;
            $updateIncident->incident_time = htmlspecialchars(strip_tags($request['incident_time']));
            $updateIncident->date_occurred = htmlspecialchars(strip_tags($request['date_occurred']));
            $updateIncident->incident_location = htmlspecialchars(strip_tags($request['incident_location']));
            $updateIncident->reasons_and_motives = htmlspecialchars(strip_tags($request['reasons_and_motives']));
            $updateIncident->tools_used = htmlspecialchars(strip_tags($request['tools_used']));
            $updateIncident->number_of_victims = htmlspecialchars(strip_tags($request['number_of_victims']));
            $updateIncident->number_of_perpetrators = htmlspecialchars(strip_tags($request['number_of_perpetrators']));
            $updateIncident->incident_status = htmlspecialchars(strip_tags($request['incident_status']));
            $updateIncident->incident_description = htmlspecialchars(strip_tags($request['incident_description']));
            $updateIncident->notes = htmlspecialchars(strip_tags($request['notes']));

            if (isset($request["incident_image"]) && !empty($request["incident_image"])) {
                $incidentImage = request()->file('incident_image');
                $incidentImagePath = 'images/incident_image/' .
                    $updateIncident->incident_number .  $incidentImage->getClientOriginalName();
                $incidentImage->move(public_path('images/incident_image/'), $incidentImagePath);
                $updateIncident->incident_image = $incidentImagePath;
            }
            if ($updateIncident->save()) {
                $StatusModificationMessage = "";
                if ($AddIncident->incident_status != $updateIncident->incident_status) {
                    $StatusModificationMessage = " وتم تعديل الحاله من " . $AddIncident->incident_status . " الى " . $updateIncident->incident_status;
                    $AddIncident->save();
                }
                $user = User::find(auth()->user()->id);
                $date = date('H:i Y-m-d');
                HelperController::NotificationsUserDepartment("لقد تمت تعديل بلاغ برقم " . $updateIncident->incident_number . $StatusModificationMessage . " نوع الجريمة " . $updateIncident->crimeType->name . " في تاريخ " . $date, $departmentId);

                activity()->performedOn($updateIncident)->event("تعديل بلاغ")->causedBy($user)
                    ->log(
                        "تم تعديل بلاغ برقم " . $updateIncident->incident_number . $StatusModificationMessage . " نوع الجريمة " . $updateIncident->crimeType->name . " في تاريخ " . $date . " بواسطة المستخدم " . $user->name . " في الوقت والتاريخ " . $date,
                    );

                toastr()->success('تمت العملية بنجاح');
                return redirect()->route("Incident.index");
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
    public function destroy(int $id)
    {
        if (auth()->user()->user_type == 'user' && auth()->user()->user_type == 'statisticOfficer') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $data = request()->validate([
                "id" => "required|integer|min:1|max:255",
            ], [
                'id.required' => "معرف البلاغ مطلوب",
                'id.integer' => "معرف البلاغ يجب أن يكون عدد صحيح",
                'id.min' => "اقل قيمة لمعرف البلاغ هي 1",
                'id.max' => "اكبر قيمة لمعرف البلاغ هي 255",
            ]);

            $incident = Incident::find(htmlspecialchars(strip_tags($data["id"])));
            if (!$incident) {
                toastr()->error('البلاغ غير موجود');
                return redirect()->back()
                    ->withInput();
            }
            $rowsAffected = $incident->delete();
            if ($rowsAffected) {
                $date = date('H:i Y-m-d');
                // اضافة الاشعار والاضافة الى سجل العمليات
                $user = User::find(auth()->user()->id); // استرداد المستخدم الحالي
                HelperController::NotificationsUserDepartment("لقد تمت ارشفة بلاغ برقم " . $incident->incident_number . " نوع الجريمة " . $incident->crime_type_id . " في تاريخ " . $date, $incident->department_id);

                activity()->event("أرشفة بلاغ")->causedBy($user)
                    ->log(
                        "تم أرشفة البلاغ " .
                            "معرف البلاغ " . $id .
                            " بواسطة المستخدم " . $user->name .
                            " الوقت والتاريخ " . $date
                    );
                // نهاية كود عملية الاشعار والاضافة الى سجل العمليات

                toastr()->success("تم الأرشفة بنجاح");
                return redirect()->back();
            } else {
                toastr()->error("العملية فشلت");
                return redirect()->back();
            }
        } catch (ValidationException $e) {
            toastr()->error($e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            toastr()->error("لا يمكنك أرشفة البلاغ لأنه هناك عمليات مرتبطة بهذا البلاغ");
            return redirect()->back();
        }
    }
}
