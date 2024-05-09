<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\SecurityWanted;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;

class reportsController extends Controller
{
    public function report_Incident()
    {
        return view('page.report.Incident.index');
    }
    public function report_Department()
    {
        return view('page.report.Department.index');
    }
    public function report_Incident_show(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "incident_status" => "required",
                "from" => "required|date|before_or_equal:tomorrow",
                "to" => "required|date|after_or_equal:from|before_or_equal:tomorrow",
            ], [
                'operation.required' => "نوع البلاغ مطلوب",
                'from.required' => "تاريخ البداية مطلوب",
                'from.date' => "تاريخ البداية يجب ان يكون تاريخ",
                "from.before_or_equal" => "لا يمكن ان يكون تاريخ البداية بعد تاريخ اليوم",
                'to.required' =>  "تاريخ النهايه مطلوب",
                'to.date' => "تاريخ التهاية يجب ان يكون تاريخ",
                "to.before_or_equal" => "لا يمكن ان يكون تاريخ النهاية بعد تاريخ اليوم",
                "to.after_or_equal" => "لا يمكن ان يكون تاريخ البداية بعد تاريخ النهاية",
            ]);
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $fromDate = $request["from"];
            $toDate = $request["to"];

            $incident= Incident::select(
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
            )->with('department', 'crimeType')->whereBetween('incident_date', [$fromDate, $toDate]);
            if (auth()->user()->user_type == 'user') {
                $incident->where('department_id', auth()->user()->department_id);
            }
            if ($request->input('incident_status') != 0) {
                $incident_status = $request->input('incident_status');
                $incident->where('incident_status', $incident_status);
            }

            $incident_statusData = $incident->get();

            $mpdf = new Mpdf(['orientation' => 'L']);
            $html = view('page.report.Incident.show', [
                "data" => $incident_statusData,
                "title" => "تقرير البلاغات",
            ])->render();
            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output('', 'S');
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="report.pdf"');
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    }
    public function report_Department_show(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "department_id" => "required",
                "from" => "required|date|before_or_equal:tomorrow",
                "to" => "required|date|after_or_equal:from|before_or_equal:tomorrow",
            ], [
                'department_id.required' => "مركز الشرطة مطلوب",
                'from.required' => "تاريخ البداية مطلوب",
                'from.date' => "تاريخ البداية يجب ان يكون تاريخ",
                "from.before_or_equal" => "لا يمكن ان يكون تاريخ البداية بعد تاريخ اليوم",
                'to.required' =>  "تاريخ النهايه مطلوب",
                'to.date' => "تاريخ التهاية يجب ان يكون تاريخ",
                "to.before_or_equal" => "لا يمكن ان يكون تاريخ النهاية بعد تاريخ اليوم",
                "to.after_or_equal" => "لا يمكن ان يكون تاريخ البداية بعد تاريخ النهاية",
            ]);
            if ($validator->fails()) {
                toastr()->error($validator->errors()->first());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $fromDate = $request["from"];
            $toDate = $request["to"];

            $department = Incident::select(
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
                'notes',
            )->with('department', 'crimeType')->whereBetween('incident_date', [$fromDate, $toDate]);
            if (auth()->user()->user_type == 'user') {
                $department->where('department_id', auth()->user()->department_id);
            }
            if ($request->input('department_id') != 0) {
                $department_id = $request->input('department_id');
                $department->where('department_id', $department_id);
            }

            $departmentData = $department->get();

            $mpdf = new Mpdf(['orientation' => 'L']);
            $html = view('page.report.Department.show', [
                "data" => $departmentData,
                "title" => "تقرير الاقسام",
            ])->render();
            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output('', 'S');
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="report.pdf"');
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    }
}
