<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
///////////////////////User/////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\api\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\api\AuthController@logout');
    Route::get('me', 'App\Http\Controllers\api\AuthController@me');
    Route::get('Notification', 'App\Http\Controllers\api\AuthController@Notification');
    Route::put('editUser', 'App\Http\Controllers\api\AuthController@editUser');
});

///////////////////////End User/////////////////////
///////////////////////Start College /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'College'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\CollegeController@showAll');
    Route::get('UniversityCalendar', 'App\Http\Controllers\api\CollegeController@UniversityCalendar');
});
///////////////////////End College /////////////////////////
///////////////////////Start Specialization /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'Specialization'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\SpecializationsController@showAll');
    Route::post('showByCollege', 'App\Http\Controllers\api\SpecializationsController@showByCollege');
    Route::post('showById', 'App\Http\Controllers\api\SpecializationsController@showById');
});
///////////////////////End Specialization /////////////////////////
///////////////////////Start Course /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'Course'
], function ($router) {
    Route::get('showByStudent', 'App\Http\Controllers\api\CourseController@showByStudent');
    Route::post('showBySpecialization', 'App\Http\Controllers\api\CourseController@showBySpecialization');
});
///////////////////////End Course /////////////////////////
///////////////////////Start News /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'News'
], function ($router) {
    Route::get('lastNews', 'App\Http\Controllers\api\NewsController@lastNews');
});
///////////////////////End News /////////////////////////
///////////////////////Start library /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'library'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\libraryController@showAll');
});
///////////////////////End library /////////////////////////
///////////////////////Start grade /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'grade'
], function ($router) {
    Route::post('semesterTask', 'App\Http\Controllers\api\gradeController@semesterTask');
    Route::post('result', 'App\Http\Controllers\api\gradeController@result');
});
///////////////////////End grade /////////////////////////
