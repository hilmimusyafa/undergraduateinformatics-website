<?php

namespace App\Services\Reservation;

use Illuminate\Support\Carbon;

final class ReservationAnswerMapper
{
    public function map(array $answers): array
    {
        $mapping = config('reservation.form_mapping', []);
        $required = config('reservation.required_fields', []);

        $byQuestionId = [];

        foreach ($answers as $answer) {
            $byQuestionId[$answer['questionId']] = $answer['answer'];
        }

        $missing = [];

        foreach ($required as $field) {
            $questionId = array_search($field, $mapping, true);
            $value = $questionId !== false ? ($byQuestionId[$questionId] ?? null) : null;

            if ($value === null || $value === '' || $value === []) {
                $missing[] = $field;
            }
        }

        if ($missing !== []) {
            throw new ReservationMappingException(
                $missing,
                [],
                'Required fields are missing: ' . implode(', ', $missing) . '.'
            );
        }

        $attributes = [];

        foreach ($mapping as $questionId => $field) {
            if (!array_key_exists($questionId, $byQuestionId)) {
                continue;
            }

            $value = $byQuestionId[$questionId];

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $attributes[$field] = match ($field) {
                'date' => $this->normalizeDate($value),
                'shift' => $this->normalizeShift($value),
                default => $this->normalizeText($value),
            };
        }

        return $attributes;
    }

    private function normalizeDate(mixed $value): string
    {
        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable $e) {
            throw new ReservationMappingException(
                ['date'],
                ['date' => ['The reservation date is not a valid date.']],
                'The reservation date is not valid.'
            );
        }
    }

    private function normalizeShift(mixed $value): string
    {
        $shift = (string) $value;

        if (preg_match('/^(\d{2}:\d{2})/', $shift, $matches) === 1) {
            $shift = $matches[1];
        }

        if (strlen($shift) === 5) {
            $shift .= ':00';
        }

        return $shift;
    }

    private function normalizeText(mixed $value): string
    {
        if (is_array($value)) {
            return implode(', ', $value);
        }

        return (string) $value;
    }
}
