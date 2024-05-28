<?php

namespace App\Http\Controllers\api;

use App\Models\College;
use App\Http\Controllers\Controller;
use Exception;

class CollegeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api'); 
    //     $this->middleware('userStatus');
    // }
    public function showAll()
    {
        try {
            $College = College::select('id', 'name')->get();
            if ($College->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد كليات'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $College]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function UniversityCalendar()
    {
        try {
            $UniversityCalendar = asset('assets/UniversityCalendar.jpg');
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $UniversityCalendar]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
}
