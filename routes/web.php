<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\FeedbackController;
use App\Models\DashboardDataset;
use App\Models\FeedbackLink;
use App\Models\ReservationLink;
use App\Models\ReservationSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

Route::get('/', [HomePageController::class, 'index'])->name('home');

Route::get('/tags/{id}', [TagController::class, 'show'])->name('viewTag');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('viewPost');
Route::get('/links', [LinkController::class, 'index_home'])->name('home.links');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/feedback', [FeedbackController::class, 'show'])->name('viewFeedback');

/*
 * Admin uses Laravel's regular web stack: session authentication, named routes,
 * Blade views, and standard form submissions.  The public site remains served
 * by its existing React/Vite entry point.
 */
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminController::class, 'loginForm'])->name('login');
        Route::post('login', [AdminController::class, 'login'])->name('loginAttempt');
    });

    Route::get('forgot-password', [AdminController::class, 'forgotForm'])->name('forgotPassword');
    Route::post('submit-email-recovery', [AdminController::class, 'submitEmailRecovery'])->name('submitEmailRecovery');
    Route::get('question-form', [AdminController::class, 'questionForm'])->name('questionForm');
    Route::post('submit-answer-recovery', [AdminController::class, 'submitAnswerRecovery'])->name('submitAnswerRecovery');
    Route::get('password-recovery-form', [AdminController::class, 'passwordRecoveryForm'])->name('passwordRecoveryForm');
    Route::post('submit-password-recovery', [AdminController::class, 'submitPasswordRecovery'])->name('submitPasswordRecovery');

    Route::middleware('auth')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('dashboard', function () {
            $dashboardTablesReady = Schema::hasTable('dashboard_datasets')
                && Schema::hasTable('dashboard_dataset_items');

            $datasets = $dashboardTablesReady
                ? DashboardDataset::with(['items' => fn ($query) => $query->orderBy('sort_order')])
                    ->orderBy('id')
                    ->get()
                    ->map(fn (DashboardDataset $dataset) => [
                        'id' => $dataset->id,
                        'title' => $dataset->title,
                        'chart_type' => $dataset->chart_type,
                        'x_label' => $dataset->x_label,
                        'y_label' => $dataset->y_label,
                        'labels' => $dataset->items->pluck('label')->values(),
                        'values' => $dataset->items->pluck('value')->values(),
                    ])
                : collect();

            return view('AdminDashboard.index', [
                'datasets' => $datasets,
                'dashboardTablesReady' => $dashboardTablesReady,
            ]);
        })->name('admin.dashboard');
        Route::get('reservation', function () {
            $reservationTableReady = Schema::hasTable('reservation_schedules');

            return view('AdminDashboard.reservation', [
                'reservationTableReady' => $reservationTableReady,
                'reservationDetailsReady' => $reservationTableReady
                    && Schema::hasColumn('reservation_schedules', 'meeting_room'),
                'reservations' => $reservationTableReady
                    ? ReservationSchedule::latest()->get()
                    : collect(),
            ]);
        })->name('admin.reservation');

        Route::get('form-link', fn () => view('AdminDashboard.feedback', [
            'feedbackLink' => FeedbackLink::query()->first(),
            'reservationLink' => Schema::hasTable('reservation_links')
                ? ReservationLink::query()->first()
                : null,
        ]))->name('admin.form-link');
        Route::put('form-link/feedback', function (Request $request) {
            $validated = $request->validate(['feedback_link' => ['required', 'url']]);
            $feedbackLink = FeedbackLink::query()->firstOrCreate([], ['link' => '']);
            $feedbackLink->update(['link' => $validated['feedback_link']]);

            return redirect()->route('admin.form-link')->with('success', 'Link feedback berhasil diperbarui.');
        })->name('admin.form-link.feedback.update');
        Route::put('form-link/reservation', function (Request $request) {
            $validated = $request->validate(['reservation_link' => ['required', 'url']]);
            $reservationLink = ReservationLink::query()->firstOrCreate([], ['link' => '']);
            $reservationLink->update(['link' => $validated['reservation_link']]);

            return redirect()->route('admin.form-link')->with('success', 'Link reservasi berhasil diperbarui.');
        })->name('admin.form-link.reservation.update');

        Route::get('logout', [AdminController::class, 'logout'])->name('logout');
        Route::resource('posts', PostController::class)->except(['show']);
        Route::resource('links', LinkController::class)->except(['show']);
        Route::resource('tags', TagController::class)->except(['show']);
        Route::resource('sections', SectionController::class)->except(['show']);

        Route::get('sections/change-order', [SectionController::class, 'changeOrder'])->name('sections.changeOrder');
        Route::post('sections/update-order', [SectionController::class, 'updateOrder'])->name('sections.updateOrder');

        Route::get('edit-password-recovery-questions', [AdminController::class, 'editPasswordRecoveryQuestion'])->name('editPasswordRecoveryQuestion');
        Route::post('update-password-recovery-questions', [AdminController::class, 'updatePasswordRecoveryQuestion'])->name('updatePasswordRecoveryQuestion');
    });
});
