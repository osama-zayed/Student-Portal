<?php

namespace App\Http\Controllers\api;

use App\Models\Library;
use App\Http\Controllers\Controller;
use Exception;

class libraryController extends Controller
{
    public function showAll()
    {
        try {
            $Library = Library::select(
                'id',
                'name',
                'image',
                'url',
                'description',
            )
                ->get();
            $transformedData = $Library->map(function ($Library) {
                return [
                    'id' => $Library->id,
                    'name' => $Library->name,
                    'url' => asset($Library->url),
                    'image' => asset($Library->image),
                    'description' => $Library->description,
                ];
            });
            if ($Library->isEmpty()) {
                return response()->json(['Status' => false, 'Message' => 'لا يوجد كتب'], 404);
            }
            return response()->json(['Status' => true, 'Message' => "تم جلب البيانات بنجاح", 'data' => $transformedData]);
        } catch (Exception $e) {
            return response()->json(['Status' => false, 'Message' => 'خطأ عند جلب البيانات'], 404);
        }
    }
}
