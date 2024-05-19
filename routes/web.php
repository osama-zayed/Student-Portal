<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(["auth", "userStatus" ,'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/Incident/get/data', [App\Http\Controllers\HomeController::class, 'Incident_get']);
    Route::get('/Incident/Archive', [App\Http\Controllers\IncidentController::class, 'showDeleted'])->name('Incident_deleted');
    Route::get('/Student/Archive', [App\Http\Controllers\StudentController::class, 'showDeleted'])->name('Student_deleted');
    Route::get('/search', [App\Http\Controllers\HomeController::class, 'searchById'])->name('searchById');
    Route::resource('Incident', App\Http\Controllers\IncidentController::class);
    Route::resource('Student', App\Http\Controllers\StudentController::class);
    Route::resource('teacher', App\Http\Controllers\teacherController::class);
    Route::resource('Specialization', App\Http\Controllers\SpecializationsController::class);
    Route::resource('College', App\Http\Controllers\CollegeController::class);
    Route::resource('College-New', App\Http\Controllers\CollegeNewsController::class);
    Route::resource('Course', App\Http\Controllers\CourseController::class);
    Route::resource('library_Book', App\Http\Controllers\LibraryController::class);
    Route::get('/Activity', [App\Http\Controllers\userController::class, 'Activity'])->name('Activity');
    Route::resource('user', App\Http\Controllers\userController::class);
    Route::get('/report/Incident', [App\Http\Controllers\reportsController::class, 'report_Incident'])->name('report_Incident');
    Route::get('/report/Incident/show', [App\Http\Controllers\reportsController::class, 'report_Incident_show'])->name('report_Incident_show');
    Route::get('/report/Department', [App\Http\Controllers\reportsController::class, 'report_Department'])->name('report_Department');
    Route::get('/report/Department/show', [App\Http\Controllers\reportsController::class, 'report_Department_show'])->name('report_Department_show');

});
Auth::routes();
