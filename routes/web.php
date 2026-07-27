<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\FeedbackController;

Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::get('/tags/{id}', [TagController::class, 'show'])->name('viewTag');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('viewPost');
Route::get('/links', [LinkController::class, 'index_home'])->name('home.links');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/feedback', [FeedbackController::class, 'show'])->name('viewFeedback');

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['guest']], function() {
        Route::get('login', [AdminController::class, 'loginForm'])->name('login');
        Route::post('login', [AdminController::class, 'login'])->name('loginAttempt');   
    });

    Route::get('forgot-password', [AdminController::class, 'forgotForm'])->name('forgotPassword');
    Route::post('submit-email-recovery', [AdminController::class, 'submitEmailRecovery'])->name('submitEmailRecovery');
    Route::get('question-form', [AdminController::class, 'questionForm'])->name('questionForm');
    Route::post('submit-answer-recovery', [AdminController::class, 'submitAnswerRecovery'])->name('submitAnswerRecovery');
    Route::get('password-recovery-form', [AdminController::class, 'passwordRecoveryForm'])->name('passwordRecoveryForm');
    Route::post('submit-password-recovery', [AdminController::class, 'submitPasswordRecovery'])->name('submitPasswordRecovery');

    Route::group(['middleware' => ['auth']], function() {
        Route::get('logout', [AdminController::class, 'logout'])->name('logout');
        Route::resource('posts', PostController::class)->except(['show']);
        Route::resource('links', LinkController::class)->except(['show']);
        Route::resource('tags', TagController::class)->except(['show']);
        Route::resource('sections', SectionController::class)->except(['show']);

        Route::get('sections/change-order', [SectionController::class, 'changeOrder'])->name('sections.changeOrder');
        Route::post('sections/update-order', [SectionController::class, 'updateOrder'])->name('sections.updateOrder');

        Route::resource('feedback', FeedbackController::class)->only(['index', 'edit', 'update']);
        Route::get('edit-password-recovery-questions', [AdminController::class, 'editPasswordRecoveryQuestion'])->name('editPasswordRecoveryQuestion');
        Route::post('update-password-recovery-questions', [AdminController::class, 'updatePasswordRecoveryQuestion'])->name('updatePasswordRecoveryQuestion');
        Route::redirect('/', route('posts.index'));
    });
});