<?php

namespace App\Services\Reservation;

use RuntimeException;

final class ReservationMappingException extends RuntimeException
{
    public function __construct(
        public readonly array $fields,
        public readonly array $fieldErrors = [],
        string $message = '',
    ) {
        parent::__construct($message !== '' ? $message : 'Reservation mapping failed.');
    }
}