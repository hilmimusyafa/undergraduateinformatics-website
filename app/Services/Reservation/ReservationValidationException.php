<?php

namespace App\Services\Reservation;

use RuntimeException;

final class ReservationValidationException extends RuntimeException
{
    public function __construct(
        public readonly array $errors,
        string $message,
    ) {
        parent::__construct($message);
    }
}