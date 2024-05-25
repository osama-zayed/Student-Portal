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
                'image',
                'description',
            )
            ->orderBy('id', 'desc')
            ->take(7)
            ->get();
            $transformedData = $CollegeNew->map(function ($CollegeNew) {
                return [
                    'id' => $CollegeNew->id,
                    'title' => $CollegeNew->title,
                    'image' => asset($CollegeNew->image),
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
