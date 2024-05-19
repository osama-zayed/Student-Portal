<?php

namespace App\Http\Controllers\api;

use App\Models\CollegeNew;
use App\Http\Controllers\Controller;
use Exception;

class NewsController extends Controller
{
    public function lastNews()
    {
        try {
            $CollegeNew = CollegeNew::select(
                'id',
                'title',
            )
                ->get();
            $transformedData = $CollegeNew->map(function ($CollegeNew, $num) {
                return [
                    'id' => $CollegeNew->id,
                    'title' => $CollegeNew->title,
                ];
            });
            if ($CollegeNew->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد اخبار'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
    public function NewsDetails()
    {
        try {
            $data =  request()->validate([
                'id' => 'required|integer|min:1',
            ], [
                'id.required' => 'حقل معرف الخبر مطلوب',
                'id.integer' => 'حقل معرف الخبر يجب ان يكون رقم',
                'id.max' => "اكبر حد لمعرف الخبر 255 حرف",
                'id.min' => "اقل حد لمعرف الخبر 1",
            ]);
            $CollegeNew = CollegeNew::select(
                'id',
                'title',
                'image',
                "description",
            )
                ->where('id', $data['id'])
                ->get();
            $transformedData = $CollegeNew->map(function ($CollegeNew) {
                return [
                    'id' => $CollegeNew->id,
                    'title' => $CollegeNew->title,
                    '' => asset($CollegeNew->image),
                    'description' => $CollegeNew->description,
                ];
            });
            if ($CollegeNew->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد اخبار'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
}
