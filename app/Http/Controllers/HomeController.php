<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Incident;
use App\Models\SecurityWanted;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\Notifications;

class HomeController extends Controller
{
    public function index()
    {
        // $initial_incident_count = Incident::query();
        // $supplementary_incident_count = Incident::query();
        // $transferred_incident_count = Incident::query();
        // $checked_incident_count = Incident::query();
        // $fake_incident_count = Incident::query();
        // if (auth()->user()->user_type == 'user') {
        //     $initial_incident_count->where('department_id', auth()->user()->department_id);
        //     $supplementary_incident_count->where('department_id', auth()->user()->department_id);
        //     $transferred_incident_count->where('department_id', auth()->user()->department_id);
        //     $checked_incident_count->where('department_id', auth()->user()->department_id);
        //     $fake_incident_count->where('department_id', auth()->user()->department_id);
        // }
        $data = [
            'CollegeCount' => College::count(),
            'SpecializationCount' => Specialization::count(),
            'TeacherCount' =>Teacher::count(),
            'StudentCount'  => Student::count(),
        ];
        return view('page.dashboard')->with('data', $data);
    }

    public function searchById()
    {
        // try {
        //     $id = request()->input('search');
        //     $SecurityWanted = SecurityWanted::where('id', $id)
        //         ->orWhere('registration_number', $id)
        //         ->first();
        //     $Incident = Incident::where('id', $id)
        //         ->orWhere('incident_number', $id)
        //         ->first();

        //     return view('page.searchResults', compact('SecurityWanted', 'Incident'));
        // } catch (Exception $e) {
        //     toastr()->error('خطأ عند جلب البيانات');
        //     return redirect()->back();
        // }
    }
    public function getStudentCountsBySpecialization()
    {
        $studentCounts = DB::table('students')
            ->select('specializations.name', DB::raw('COUNT(*) as count'))
            ->join('specializations', 'students.specialization_id', '=', 'specializations.id')
            ->groupBy('specializations.name')
            ->get();
    
        return response()->json(['data' => $studentCounts]);
    }
    public function Notifications(Request $request)
    {
        try {
            $users = Student::query();
            if ($request['college_id'] != 0) {
                $users->where('college_id', $request['college_id']);
            }
            if ($request['specialization_id'] != 0) {
                $users->where('specialization_id', $request['specialization_id']);
            }
            if ($request['Student_id'] != 0) {
                $users->where('id', $request['Student_id']);
            }
    
            $usersData = $users->get();
            if ($usersData->isNotEmpty()) {
                foreach ($usersData as $user) {
                    $user->notify(new Notifications([
                        "body" => $request['NoticeContent']
                    ]));
                }
            }
            toastr()->success('تمت العملية بنجاح');
            return redirect()-> route('home');
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()-> route('home');
        }
    }
}
