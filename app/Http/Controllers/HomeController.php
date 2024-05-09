<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\SecurityWanted;
use Exception;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $initial_incident_count = Incident::query();
        $supplementary_incident_count = Incident::query();
        $transferred_incident_count = Incident::query();
        $checked_incident_count = Incident::query();
        $fake_incident_count = Incident::query();
        if (auth()->user()->user_type == 'user') {
            $initial_incident_count->where('department_id', auth()->user()->department_id);
            $supplementary_incident_count->where('department_id', auth()->user()->department_id);
            $transferred_incident_count->where('department_id', auth()->user()->department_id);
            $checked_incident_count->where('department_id', auth()->user()->department_id);
            $fake_incident_count->where('department_id', auth()->user()->department_id);
        }
        $data = [
            'incident_count' => $initial_incident_count->count() ?? 0,
            'initial_incident_count' => $initial_incident_count->where('incident_status', 'أولي')->count() ?? 0,
            'supplementary_incident_count' => $supplementary_incident_count->where('incident_status', 'تكميلي')->count() ?? 0,
            'transferred_incident_count' => $transferred_incident_count->where('incident_status', 'محول')->count() ?? 0,
            'checked_incident_count' => $checked_incident_count->where('incident_status', 'مشيك')->count() ?? 0,
            'fake_incident_count' => $fake_incident_count->where('incident_status', 'وهمي')->count() ?? 0,
            'security_wanted_count' => SecurityWanted::count() ?? 0,
            'deleted_security_wanted_count' => SecurityWanted::onlyTrashed()->count() ?? 0,
        ];
        return view('page.dashboard')->with('data', $data);
    }

    public function searchById()
    {
        try {
            $id = request()->input('search');
            $SecurityWanted = SecurityWanted::where('id', $id)
                ->orWhere('registration_number', $id)
                ->first();
            $Incident = Incident::where('id', $id)
                ->orWhere('incident_number', $id)
                ->first();

            return view('page.searchResults', compact('SecurityWanted', 'Incident'));
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
    }
    public function Incident_get()
    {

        $Incidents = DB::table('Incidents')
            ->groupBy('incident_status')
            ->selectRaw('incident_status, COUNT(incident_status) as Counts');
        if (auth()->user()->user_type == 'user') {
            $Incidents->where('department_id', auth()->user()->department_id);
        }
        $IncidentsData = $Incidents->get();
        return response()->json(["data" => $IncidentsData]);
    }
}
