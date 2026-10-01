<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReservationLink;
use App\Models\ReservationSchedule;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function show(Request $request): View
    {
        $page = PageMeta::page('reservation');

        $schedules = ReservationSchedule::query()
            ->orderBy('date', 'desc')
            ->orderBy('shift', 'asc')
            ->get();

        return view('ReservationPage', [
            'title' => $page['title'],
            'description' => $page['description'],
            'reservationLink' => ReservationLink::configured()->first(),
            'schedules' => $schedules,
            'allowedShifts' => config('reservation.allowed_shifts', ['09:00:00', '13:00:00', '15:00:00']),
        ]);
    }
}