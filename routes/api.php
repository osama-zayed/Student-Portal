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
///////////////////////Start Project /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'Project'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\ProjectsController@showAll');
    Route::post('show', 'App\Http\Controllers\api\ProjectsController@show');
    Route::post('Add', 'App\Http\Controllers\api\ProjectsController@Add');
    Route::put('updata', 'App\Http\Controllers\api\ProjectsController@updata');
});
///////////////////////End Project /////////////////////////
///////////////////////Start Province /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'Province'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\ProvincesController@showAll');
    Route::post('show', 'App\Http\Controllers\api\ProvincesController@show');
    Route::post('Add', 'App\Http\Controllers\api\ProvincesController@Add');
    Route::put('updata', 'App\Http\Controllers\api\ProvincesController@updata');
});
///////////////////////End Province /////////////////////////
///////////////////////Start Service /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'Service'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\ServicesController@showAll');
    Route::post('show', 'App\Http\Controllers\api\ServicesController@show');
    Route::post('Add', 'App\Http\Controllers\api\ServicesController@Add');
    Route::put('updata', 'App\Http\Controllers\api\ServicesController@updata');
});
///////////////////////End Service /////////////////////////
///////////////////////Start Station /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'Station'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\StationsController@showAll');
    Route::post('show', 'App\Http\Controllers\api\StationsController@show');
    Route::post('Add', 'App\Http\Controllers\api\StationsController@Add');
    Route::post('updata', 'App\Http\Controllers\api\StationsController@updata');
});
///////////////////////End Station /////////////////////////
///////////////////////Start WarehouseReceivingOperation /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'WarehouseReceivingOperation'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\WarehouseReceivingOperationController@showAll');
    Route::post('show', 'App\Http\Controllers\api\WarehouseReceivingOperationController@show');
    Route::post('Add', 'App\Http\Controllers\api\WarehouseReceivingOperationController@Add');
    Route::post('edit', 'App\Http\Controllers\api\WarehouseReceivingOperationController@edit');
});
///////////////////////End Station /////////////////////////
///////////////////////Start WarehouseSupplyOperation /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'WarehouseSupplyOperation'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\WarehouseSupplyOperationsController@showAll');
    Route::post('show', 'App\Http\Controllers\api\WarehouseSupplyOperationsController@show');
    Route::post('Add', 'App\Http\Controllers\api\WarehouseSupplyOperationsController@Add');
    Route::post('edit', 'App\Http\Controllers\api\WarehouseSupplyOperationsController@edit');
});
///////////////////////End WarehouseSupplyOperation /////////////////////////
///////////////////////Start WarehouseTransferOperation /////////////////////
Route::group([
    'middleware' => 'api',
    'prefix' => 'WarehouseTransferOperation'
], function ($router) {
    Route::get('showAll', 'App\Http\Controllers\api\WarehouseTransferOperationsController@showAll');
    Route::post('show', 'App\Http\Controllers\api\WarehouseTransferOperationsController@show');
    Route::post('Add', 'App\Http\Controllers\api\WarehouseTransferOperationsController@Add');
    Route::post('edit', 'App\Http\Controllers\api\WarehouseTransferOperationsController@edit');
});
///////////////////////End WarehouseTransferOperation /////////////////////////