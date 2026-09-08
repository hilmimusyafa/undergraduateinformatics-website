<?php

namespace App\Services\Reservation;

final class ReservationMetadata
{
    public function build(): array
    {
        $mapping = config('reservation.form_mapping', []);

        return [
            'dateQuestionId' => (string) array_search('date', $mapping, true),
            'shiftQuestionId' => (string) array_search('shift', $mapping, true),
            'allowedDays' => config('reservation.allowed_days', []),
        ];
    }
}