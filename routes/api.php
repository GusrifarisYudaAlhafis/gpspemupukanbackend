<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('devices', DeviceController::class);
    Route::get('spreader-count', [DeviceController::class, 'spreaderCount']);
    Route::get('tracker-count', [DeviceController::class, 'trackerCount']);
    Route::apiResource('tracks', TrackController::class);
    Route::get('imei', [TrackController::class, 'imei']);
    Route::apiResource('units', UnitController::class);
});
