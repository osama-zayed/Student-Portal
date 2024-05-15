<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\HelperController;


class SpecializationsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api'); 
    //     $this->middleware('userStatus'); 
    // }
    public function showAll()
    {
        try {
            $Specialization = Specialization::with('college')->select(
                'id',
                'name',
                'college_id',
                'Price',
                'Number_of_years_of_study',
                'educational_qualification',
                'lowest_acceptance_rate',
            )->get();
            $transformedData = $Specialization->map(function ($Specialization) {
                return [
                    'id' => $Specialization->id,
                    'name' => $Specialization->name,
                    'College_name' => $Specialization->college->name,
                    'Price' => $Specialization->Price . '$',
                    'Number_of_years_of_study' => $Specialization->Number_of_years_of_study,
                    'educational_qualification' => $Specialization->educational_qualification,
                    'lowest_acceptance_rate' => $Specialization->lowest_acceptance_rate .'%',
                ];
            });
            if ($Specialization->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد تخصصات'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function showByCollege()
    {
        try {
            $data =  request()->validate([
                'college_id' => 'required|integer|min:1',
            ], [
                'college_id.required' => 'حقل معرف الكلية مطلوب',
                'college_id.integer' => 'حقل معرف الكلية يجب ان يكون رقم',
                'college_id.max' => "اكبر حد لمعرف الكلية 255 حرف",
                'college_id.min' => "اقل حد لمعرف الكلية 1",
            ]);

            $Specialization = Specialization::with('college')->where('college_id',$data['college_id'])->select(
                'id',
                'name',
                'college_id',
                'Price',
                'Number_of_years_of_study',
                'educational_qualification',
                'lowest_acceptance_rate',
            )->get();
            $transformedData = $Specialization->map(function ($Specialization) {
                return [
                    'id' => $Specialization->id,
                    'name' => $Specialization->name,
                    'College_name' => $Specialization->college->name,
                    'Price' => $Specialization->Price . '$',
                    'Number_of_years_of_study' => $Specialization->Number_of_years_of_study,
                    'educational_qualification' => $Specialization->educational_qualification,
                    'lowest_acceptance_rate' => $Specialization->lowest_acceptance_rate .'%',
                ];
            });
            if ($Specialization->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد تخصصات'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $errorMessages[] = $error[0];
            }
            $mergedMessage = implode(" و ", $errorMessages);

            return response()->json(['Status' => false, 'Message' => $mergedMessage], 400);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'حدث خطأ غير متوقع'], 500);
        }
    }
    public function showById()
    {
        try {
            $data =  request()->validate([
                'id' => 'required|integer|min:1',
            ], [
                'id.required' => 'حقل معرف التخصص مطلوب',
                'id.integer' => 'حقل معرف التخصص يجب ان يكون رقم',
                'id.max' => "اكبر حد لمعرف التخصص 255 حرف",
                'id.min' => "اقل حد لمعرف التخصص 1",
            ]);

            $Specialization = Specialization::with('college')->where('id',$data['id'])->select(
                'id',
                'name',
                'college_id',
                'Price',
                'Number_of_years_of_study',
                'educational_qualification',
                'lowest_acceptance_rate',
            )->get();
            $transformedData = $Specialization->map(function ($Specialization) {
                return [
                    'id' => $Specialization->id,
                    'name' => $Specialization->name,
                    'College_name' => $Specialization->college->name,
                    'Price' => $Specialization->Price . '$',
                    'Number_of_years_of_study' => $Specialization->Number_of_years_of_study,
                    'educational_qualification' => $Specialization->educational_qualification,
                    'lowest_acceptance_rate' => $Specialization->lowest_acceptance_rate .'%',
                ];
            });
            if ($Specialization->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد تخصصات'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $errorMessages[] = $error[0];
            }
            $mergedMessage = implode(" و ", $errorMessages);

            return response()->json(['Status' => false, 'Message' => $mergedMessage], 400);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'حدث خطأ غير متوقع'], 500);
        }
    }
}
