<?php

namespace App\Http\Controllers;

use App\Models\College;
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
        $data = [
            'CollegeCount' => College::count(),
            'SpecializationCount' => Specialization::count(),
            'TeacherCount' => Teacher::count(),
            'StudentCount'  => Student::count(),
        ];
        return view('page.dashboard')->with('data', $data);
    }

    public function searchById()
    {
        try {
            $searchTerm = request()->input('search');
            $users = Student::where('full_name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('academic_id', $searchTerm)
                ->get();

            return view('page.searchResults', compact('users'));
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->back();
        }
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
        $user = auth()->user();
        if ($user->user_type == 'registration' || $user->user_type == 'control') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
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
            return redirect()->route('home');
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return redirect()->route('home');
        }
    }
    public function UpdateImageUniversityCalendar(Request $request)
    {
        $user = auth()->user();
        if ($user->user_type == 'student_affairs' || $user->user_type == 'control') {
            toastr()->error("غير مصرح لك");
            return redirect()->back();
        }
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'file.required' => 'الصورة مطلوبة',
                'file.image' => 'يجب ان يكون الملف من نوع صوره',
                'file.mimes' => 'يجب ان يكون الملف باحد الامتدادات التالية jpeg,png,jpg,gif,svg ',
                'file.max' => 'اقصى حجم للصورة 2048',
            ]);
            $updateLibraryFile = $request->file('file');
            $updateLibraryFile->move(public_path('assets/'), 'UniversityCalendar.jpg');
            toastr()->success('تمت العملية بنجاح');


            return redirect()->route('home');
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return redirect()->route('home');
        }
    }
}
