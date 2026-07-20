<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTagController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiPostController;
use App\Http\Controllers\ApiFeedbackLinkController;
use App\Http\Controllers\ApiImportantLinkController;
use App\Http\Controllers\ApiImportantSectionController;
use App\Http\Controllers\ApiPasswordRecoveryController;
use App\Http\Controllers\ApiEditPasswordRecoveryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomePageController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home', [HomePageController::class, 'apiIndex']);

Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('posts', ApiPostController::class)->middleware('auth:sanctum');
Route::apiResource('tags', ApiTagController::class)->middleware('auth:sanctum');
Route::apiResource('important-sections', ApiImportantSectionController::class)->middleware('auth:sanctum');
Route::apiResource('important-links', ApiImportantLinkController::class)->middleware('auth:sanctum');
Route::apiResource('password-recoveries', ApiPasswordRecoveryController::class);
Route::apiResource('edit-password-recoveries', ApiEditPasswordRecoveryController::class)->middleware('auth:sanctum');
Route::apiResource('feedback-links', ApiFeedbackLinkController::class)->middleware('auth:sanctum');
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'fetch']);
    Route::delete('/cleardata', [DashboardController::class, 'cleardata']);
    Route::post('/extract', [DashboardController::class, 'extract']);
    Route::post('/pushdata', [DashboardController::class, 'pushdata']);
});