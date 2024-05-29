<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.Promotion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromotionRequest $request)
    {
        try {

            $Students = Student::select(
                'id',
                'specialization_id',
                'semester_num',
                'academic_year',
                'status'
            )
                ->where('specialization_id', $request['from_specialization_id'])
                ->where('semester_num', $request['from_semester_num'])
                ->where('academic_year', $request['academic_year'])
                ->get();

            foreach ($Students as $Student) {
                $Student->update([
                    'specialization_id' => $request['to_specialization_id'],
                    'semester_num' => $request['to_semester_num'],
                    'academic_year' => $request['academic_year_new'],
                    'status' => 'ناجح'
                ]);

                Promotion::create([
                    'student_id' => $Student->id,
                    'from_semester_num' => $request['from_semester_num'],
                    'to_semester_num' => $request['to_semester_num'],
                    'from_specialization_id' => $request['from_specialization_id'],
                    'to_specialization_id' => $request['to_specialization_id'],
                    'academic_year' => $request['academic_year'],
                    'academic_year_new' => $request['academic_year_new'],
                    'status' => $Student->status,
                ]);
            }

            toastr()->success('تمت العملية بنجاح');
            return redirect()->back();
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
