<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTagController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiPostController;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\ApiFeedbackLinkController;
use App\Http\Controllers\ApiImportantLinkController;
use App\Http\Controllers\ApiImportantSectionController;
use App\Http\Controllers\ApiPasswordRecoveryController;
use App\Http\Controllers\ApiEditPasswordRecoveryController;
use App\Http\Controllers\DashboardController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login',[ApiAuthController::class,'login'])->name('login');
Route::post('/logout',[ApiAuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('/informasi/store',[ApiPostController::class,'store'])->middleware('auth:sanctum');
Route::post('/informasi/update/{id}',[ApiPostController::class,'update'])->middleware('auth:sanctum');
Route::delete('/informasi/delete/{id}',[ApiPostController::class,'destroy'])->middleware('auth:sanctum');
Route::get('/informasi',[ApiPostController::class,'index'])->middleware('auth:sanctum');
Route::apiResource('tag', ApiTagController::class)->middleware('auth:sanctum');
Route::apiResource('importantSection', ApiImportantSectionController::class)->middleware('auth:sanctum');
Route::apiResource('importantLink', ApiImportantLinkController::class)->middleware('auth:sanctum');
Route::apiResource('passwordRecovery', ApiPasswordRecoveryController::class);
Route::apiResource('editPasswordRecovery', ApiEditPasswordRecoveryController::class)->middleware('auth:sanctum');
Route::apiResource('editPasswordRecovery', ApiEditPasswordRecoveryController::class)->middleware('auth:sanctum');
Route::apiResource('feedbackLink', ApiFeedbackLinkController::class)->middleware('auth:sanctum');
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'fetch']);
    Route::delete('/cleardata', [DashboardController::class, 'cleardata']);
    Route::post('/extract', [DashboardController::class, 'extract']);
        // ->middleware('auth:sanctum');
    Route::post('/pushdata', [DashboardController::class, 'pushdata']);
        // ->middleware('auth:sanctum');
});