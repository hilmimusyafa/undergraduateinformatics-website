<?php

namespace App\Services\Reservation;

use App\Models\ReservationLink;
use App\Models\ReservationSchedule;
use App\Services\MsForms\MsFormsClient;
use App\Services\MsForms\MsFormsException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final class ReservationSubmissionService
{
    public function __construct(
        private readonly ReservationAnswerMapper $mapper,
        private readonly BeritaAcaraPdfGenerator $pdfGenerator,
        private readonly MsFormsClient $msFormsClient,
    ) {
    }

    public function submit(array $answers): ?ReservationSchedule
    {
        $attributes = $this->mapper->map($answers);

        $link = ReservationLink::configured()->first();

        if (!$link) {
            throw new ReservationFormUnavailableException('Reservation form is unavailable.');
        }

        $this->assertValidDate($attributes);
        $this->assertValidShift($attributes);
        $this->assertNoConflict($attributes);

        try {
            $target = $this->msFormsClient->resolve($link->link);
            $this->msFormsClient->submitAnswers(
                $target,
                $this->msAnswers($answers),
                now()->toIso8601String()
            );
        } catch (MsFormsException $e) {
            Log::error('Reservation form submit failed: ' . $e->getMessage());

            throw $e;
        }

        try {
            $schedule = ReservationSchedule::create($attributes);
        } catch (\Throwable $e) {
            if ($this->isUniqueViolation($e)) {
                throw new ReservationValidationException(
                    ['shift' => ['The schedule on this date and session is already full. Please select another date or session.']],
                    'The schedule is already full.'
                );
            }

            Log::critical('Reservation stored in Microsoft Forms but the local record failed to save: ' . $e->getMessage() . ' — attributes: ' . json_encode($attributes));

            return null;
        }

        $documentLink = $this->pdfGenerator->generate($schedule);

        if ($documentLink) {
            $schedule->document_link = $documentLink;
            $schedule->save();
        }

        return $schedule;
    }

    private function assertValidDate(array $attributes): void
    {
        try {
            $day = Carbon::parse($attributes['date'])->dayOfWeekIso;
        } catch (\Throwable $e) {
            throw new ReservationValidationException(
                ['date' => ['The reservation date is not a valid date.']],
                'The reservation date is not valid.'
            );
        }

        if (!in_array($day, config('reservation.allowed_days', []), true)) {
            throw new ReservationValidationException(
                ['date' => ['The reservation date must be a Monday, Tuesday, Thursday, or Friday.']],
                'The reservation date is not available.'
            );
        }
    }

    private function assertValidShift(array $attributes): void
    {
        if (! in_array($attributes['shift'], config('reservation.allowed_shifts', []), true)) {
            throw new ReservationValidationException(
                ['shift' => ['The selected session is not available.']],
                'The selected session is not available.'
            );
        }
    }

    private function isUniqueViolation(\Throwable $e): bool
    {
        $pdo = $e instanceof \Illuminate\Database\QueryException ? $e->getPrevious() : $e;

        return $pdo instanceof \PDOException && (string) $pdo->getCode() === '23000';
    }

    private function assertNoConflict(array $attributes): void
    {
        $conflict = ReservationSchedule::query()
            ->where('date', $attributes['date'])
            ->where('shift', $attributes['shift'])
            ->exists();

        if ($conflict) {
            throw new ReservationValidationException(
                ['shift' => ['The schedule on this date and session is already full. Please select another date or session.']],
                'The schedule is already full.'
            );
        }
    }

    private function msAnswers(array $answers): array
    {
        return array_map(
            static fn (array $answer) => [
                'questionId' => $answer['questionId'],
                'answer1' => is_array($answer['answer'])
                    ? json_encode($answer['answer'])
                    : (string) $answer['answer'],
            ],
            $answers
        );
    }
}