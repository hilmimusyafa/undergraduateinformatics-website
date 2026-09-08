<?php

namespace App\Services\Reservation;

use App\Models\ReservationSchedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class BeritaAcaraPdfGenerator
{
    public function generate(ReservationSchedule $schedule): ?string
    {
        try {
            $pdf = Pdf::loadView('pdf.berita_acara', ['schedule' => $schedule]);

            $fileName = 'berita_acara_' . $schedule->id . '_' . time() . '.pdf';
            $directory = public_path('beritaacara');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $pdf->save($directory . '/' . $fileName);

            return url('beritaacara/' . $fileName);
        } catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());

            return null;
        }
    }
}