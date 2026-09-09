<?php

namespace App\Services\Reservation;

use App\Models\ReservationSchedule;
use Illuminate\Support\Carbon;

final class ReservationAvailabilityService
{
    public function isAvailable(string $date, string $shift): bool
    {
        $this->assertValidDate($date);
        $this->assertValidShift($shift);

        $normalizedDate = Carbon::parse($date)->format('Y-m-d');
        $normalizedShift = $this->normalizeShift($shift);

        return ! ReservationSchedule::query()
            ->where('date', $normalizedDate)
            ->where('shift', $normalizedShift)
            ->exists();
    }

    private function assertValidDate(string $date): void
    {
        try {
            $day = Carbon::parse($date)->dayOfWeekIso;
        } catch (\Throwable $e) {
            throw new ReservationValidationException(
                ['date' => ['The reservation date is not a valid date.']],
                'The reservation date is not valid.'
            );
        }

        if (! in_array($day, config('reservation.allowed_days', []), true)) {
            throw new ReservationValidationException(
                ['date' => ['The reservation date must be a Monday, Tuesday, Thursday, or Friday.']],
                'The reservation date is not available.'
            );
        }
    }

    private function assertValidShift(string $shift): void
    {
        $normalized = $this->normalizeShift($shift);

        if (preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $normalized) !== 1) {
            throw new ReservationValidationException(
                ['shift' => ['The selected session is not available.']],
                'The selected session is not available.'
            );
        }
    }

    private function normalizeShift(string $shift): string
    {
        if (preg_match('/^(\d{2}:\d{2})/', $shift, $matches) === 1) {
            $shift = $matches[1];
        }

        if (strlen($shift) === 5) {
            $shift .= ':00';
        }

        return $shift;
    }
}
