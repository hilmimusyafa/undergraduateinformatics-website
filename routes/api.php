<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\EditPasswordRecoveryController;
use App\Http\Controllers\Api\Admin\FeedbackLinkController;
use App\Http\Controllers\Api\Admin\ImportantLinkController;
use App\Http\Controllers\Api\Admin\ImportantSectionController;
use App\Http\Controllers\Api\Admin\PasswordRecoveryController;
use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\ReservationLinkController;
use App\Http\Controllers\Api\Admin\TagController as AdminTagController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LinkController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home', [HomeController::class, 'index']);

Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{slug}', [TagController::class, 'show']);

Route::get('/posts/search', [SearchController::class, 'search']);

Route::get('/posts/{slug}', [PostController::class, 'show']);

Route::get('/links', [LinkController::class, 'index']);

Route::get('/feedback', [FeedbackController::class, 'show']);
Route::post('/feedback', [FeedbackController::class, 'store']);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/informasi/store', [AdminPostController::class, 'store'])->middleware('auth:sanctum');
Route::post('/informasi/update/{id}', [AdminPostController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/informasi/delete/{id}', [AdminPostController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/informasi', [AdminPostController::class, 'index'])->middleware('auth:sanctum');
Route::apiResource('tag', AdminTagController::class)->middleware('auth:sanctum');
Route::apiResource('importantSection', ImportantSectionController::class)->middleware('auth:sanctum');
Route::apiResource('importantLink', ImportantLinkController::class)->middleware('auth:sanctum');
Route::apiResource('passwordRecovery', PasswordRecoveryController::class);
Route::apiResource('editPasswordRecovery', EditPasswordRecoveryController::class)->middleware('auth:sanctum');
Route::apiResource('feedbackLink', FeedbackLinkController::class)->middleware('auth:sanctum');
Route::prefix('reservation')->group(function () {
    Route::get('availability', [ReservationController::class, 'availability']);
    Route::get('/', [ReservationController::class, 'show']);
    Route::post('/', [ReservationController::class, 'store'])->middleware('throttle:10,1');
    Route::apiResource('link', ReservationLinkController::class);
    // ->middleware('auth:sanctum');
});
