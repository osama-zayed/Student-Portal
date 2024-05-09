<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(["auth", "userStatus"])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/Incident/get/data', [App\Http\Controllers\HomeController::class, 'Incident_get']);
    Route::get('/Incident/Archive', [App\Http\Controllers\IncidentController::class, 'showDeleted'])->name('Incident_deleted');
    Route::get('/Security_wanted/Archive', [App\Http\Controllers\SecurityWantedController::class, 'showDeleted'])->name('Security_wanted_deleted');
    Route::get('/search', [App\Http\Controllers\HomeController::class, 'searchById'])->name('searchById');
    Route::resource('Incident', App\Http\Controllers\IncidentController::class);
    Route::resource('Security_wanted', App\Http\Controllers\SecurityWantedController::class);
    Route::resource('Department', App\Http\Controllers\DepartmentsController::class);
    Route::resource('Crime', App\Http\Controllers\CrimeController::class);
    Route::get('/Activity', [App\Http\Controllers\userController::class, 'Activity'])->name('Activity');
    Route::resource('user', App\Http\Controllers\userController::class);
    Route::get('/report/Incident', [App\Http\Controllers\reportsController::class, 'report_Incident'])->name('report_Incident');
    Route::get('/report/Incident/show', [App\Http\Controllers\reportsController::class, 'report_Incident_show'])->name('report_Incident_show');
    Route::get('/report/Department', [App\Http\Controllers\reportsController::class, 'report_Department'])->name('report_Department');
    Route::get('/report/Department/show', [App\Http\Controllers\reportsController::class, 'report_Department_show'])->name('report_Department_show');

});
Auth::routes();
