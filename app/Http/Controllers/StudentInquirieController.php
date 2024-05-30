<?php

namespace App\Http\Controllers;

use App\Models\studentInquirie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentInquirieController extends Controller
{
 
    public function index()
    {
        try {
            $inquirie_type = request()->input('inquirie_type', 'complaint');
            $pageSize = 100;
            $page = request()->input('page', 1);
            if ($page < 1) $page = 1;
            $skip = ($page - 1) * $pageSize;
            $totalrequest = studentInquirie::count();
            $totalPages = ceil($totalrequest / $pageSize);
            $Student = studentInquirie::skip($skip)->take($pageSize)
                ->where('inquirie_type', $inquirie_type)
                ->orderBy('id', 'desc')
                ->with('student')->get();

            if ($Student->isEmpty()) {
                toastr()->error('لا يوجد بيانات');
            }

            return view("page.StudentInquirie.index", [
                'data' => $Student,
                "page" => $page,
                "title" => ($inquirie_type == 'complaint') ? 'شكوى' : 'استفسارات',
                "totalPages" => $totalPages,
            ]);
        } catch (Exception $e) {
            toastr()->error('خطأ عند جلب البيانات');
            return view("page.StudentInquirie.index", [
                "page" => $page,
                "title" => ($inquirie_type == 'complaint') ? 'شكوى' : 'استفسارات',
                "totalPages" => $totalPages,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(studentInquirie $studentInquirie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(studentInquirie $studentInquirie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
// dd($request['id']);
        try {
            $credentials = $request->validate([
                'reply_message' => ['required', 'string', 'max:1000', 'min:2'],
                'status' => ['required', 'string', 'max:255', 'min:2'],
            ], [
                'reply_message.required' => 'رساله الرد مطلوبة',
                'reply_message.max' => "الحد الأقصى لعدد الأحرف لرد الشكوى هو 1000",
                'reply_message.min' => "الحد الأدنى لعدد الأحرف لرد الشكوى هو 2",
                'status.required' => 'الحالة مطلوبة',
                'status.max' => "الحد الأقصى لعدد الأحرف لحالة الشكوى هو 255",
                'status.min' => "الحد الأدنى لعدد الأحرف لحالة الشكوى هو 2",
            ]);

            $inquiry = studentInquirie::find($request['id']);
            $inquiry->status = $request['status'];
            $inquiry->reply_message = $request['reply_message'];
            $inquiry->resolved_at = now();
            $inquiry->save();
            toastr()->success('تمت العملية بنجاح');
            return redirect()->back();
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $errorMessages[] = $error[0];
            }
            $mergedMessage = implode(" و ", $errorMessages);

            toastr()->error($mergedMessage);
            return redirect()->back()->withInput();
        } catch (Exception $e) {
            toastr()->error('العملية فشلت');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(studentInquirie $studentInquirie)
    {
        //
    }
}
