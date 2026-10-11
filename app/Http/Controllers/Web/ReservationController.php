<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReservationLink;
use App\Models\ReservationSchedule;
use App\Services\Reservation\BeritaAcaraPdfGenerator;
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

    public function beritaAcara(string $id)
    {
        $schedule = ReservationSchedule::findOrFail($id);

        if ($schedule->document_link) {
            $urlPath = parse_url($schedule->document_link, PHP_URL_PATH);
            if ($urlPath) {
                $filePath = public_path(ltrim($urlPath, '/'));
                if (file_exists($filePath) && is_file($filePath)) {
                    return response()->file($filePath, [
                        'Content-Type' => 'application/pdf',
                    ]);
                }
            }
        }

        $documentLink = app(BeritaAcaraPdfGenerator::class)->generate($schedule);
        if ($documentLink) {
            $schedule->document_link = $documentLink;
            $schedule->save();

            $urlPath = parse_url($documentLink, PHP_URL_PATH);
            if ($urlPath) {
                $filePath = public_path(ltrim($urlPath, '/'));
                if (file_exists($filePath) && is_file($filePath)) {
                    return response()->file($filePath, [
                        'Content-Type' => 'application/pdf',
                    ]);
                }
            }

            return redirect($documentLink);
        }

        return view('pdf.berita_acara', ['schedule' => $schedule]);
    }
}