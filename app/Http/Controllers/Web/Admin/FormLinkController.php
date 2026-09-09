<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackLink;
use App\Models\ReservationLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FormLinkController extends Controller
{
    public function show()
    {
        return view('AdminDashboard.feedback', [
            'feedbackLink' => FeedbackLink::query()->first(),
            'reservationLink' => Schema::hasTable('reservation_links')
                ? ReservationLink::query()->first()
                : null,
        ]);
    }

    public function updateFeedback(Request $request)
    {
        $validated = $request->validate(['feedback_link' => ['required', 'url']]);
        $feedbackLink = FeedbackLink::query()->firstOrCreate([], ['link' => '']);
        $feedbackLink->update(['link' => $validated['feedback_link']]);

        return redirect()->route('admin.form-link')->with('success', 'Link feedback berhasil diperbarui.');
    }

    public function updateReservation(Request $request)
    {
        $validated = $request->validate(['reservation_link' => ['required', 'url']]);
        $reservationLink = ReservationLink::query()->firstOrCreate([], ['link' => '']);
        $reservationLink->update(['link' => $validated['reservation_link']]);

        return redirect()->route('admin.form-link')->with('success', 'Link reservasi berhasil diperbarui.');
    }
}