<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(["auth", "userStatus"])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/search', [App\Http\Controllers\HomeController::class, 'searchById'])->name('searchById');
    Route::post('/Notifications', [App\Http\Controllers\HomeController::class, 'Notifications'])->name('Notifications');
    Route::get('/getStudentCountsBySpecialization', [App\Http\Controllers\HomeController::class, 'getStudentCountsBySpecialization'])->name('getStudentCountsBySpecialization');
    Route::post('/Update/Image/University/Calendar', [App\Http\Controllers\HomeController::class, 'UpdateImageUniversityCalendar'])->name('UpdateImageUniversityCalendar');
    Route::resource('Student', App\Http\Controllers\StudentController::class);
    Route::put('StudentStatus',[ App\Http\Controllers\StudentController::class,'StudentStatus'])->name('Student.StudentStatus');
    Route::resource('Result', App\Http\Controllers\ResultController::class);
    Route::resource('Promotion', App\Http\Controllers\PromotionController::class);
    Route::resource('studentInquirie', App\Http\Controllers\studentInquirieController::class);
    Route::resource('SchoolYear', App\Http\Controllers\SchoolYearController::class);
    Route::resource('SemesterTask', App\Http\Controllers\SemesterTaskController::class);
    Route::resource('teacher', App\Http\Controllers\teacherController::class);
    Route::resource('Specialization', App\Http\Controllers\SpecializationsController::class);
    Route::resource('College', App\Http\Controllers\CollegeController::class);
    Route::resource('College-New', App\Http\Controllers\CollegeNewsController::class);
    Route::resource('Course', App\Http\Controllers\CourseController::class);
    Route::resource('library_Book', App\Http\Controllers\LibraryController::class);
    Route::get('/Activity', [App\Http\Controllers\userController::class, 'Activity'])->name('Activity');
    Route::resource('user', App\Http\Controllers\userController::class);
    Route::get('/get-specializations', [App\Http\Controllers\SpecializationsController::class, 'getSpecializations'])->name('get-specializations');
    Route::get('/get-courses', [App\Http\Controllers\CollegeController::class, 'getCourses'])->name('get-courses');
    Route::get('/getSemesterTaskData', [App\Http\Controllers\SemesterTaskController::class, 'getSemesterTaskData'])->name('getSemesterTaskData');
    Route::get('/getResultData', [App\Http\Controllers\ResultController::class, 'getResultData'])->name('getResultData');
    Route::get('/getSemesterTask', [App\Http\Controllers\StudentController::class, 'semesterTask']);
    Route::get('/ResultData', [App\Http\Controllers\StudentController::class, 'ResultData']);
});
Auth::routes();
