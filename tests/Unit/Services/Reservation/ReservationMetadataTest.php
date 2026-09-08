<?php

namespace Tests\Unit\Services\Reservation;

use App\Services\Reservation\ReservationMetadata;
use Tests\TestCase;

class ReservationMetadataTest extends TestCase
{
    public function test_it_builds_metadata_from_config(): void
    {
        config([
            'reservation.form_mapping' => [
                'd1111111111111111111111111111111' => 'date',
                's2222222222222222222222222222222' => 'shift',
                'r3333333333333333333333333333333' => 'requested_by',
            ],
            'reservation.allowed_days' => [1, 2, 4, 5],
        ]);

        $this->assertSame([
            'dateQuestionId' => 'd1111111111111111111111111111111',
            'shiftQuestionId' => 's2222222222222222222222222222222',
            'allowedDays' => [1, 2, 4, 5],
        ], (new ReservationMetadata)->build());
    }
}