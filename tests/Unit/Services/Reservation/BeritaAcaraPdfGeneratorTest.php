<?php

namespace Tests\Unit\Services\Reservation;

use App\Models\ReservationSchedule;
use App\Services\Reservation\BeritaAcaraPdfGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class BeritaAcaraPdfGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_writes_a_pdf_and_returns_its_public_url(): void
    {
        $schedule = ReservationSchedule::create([
            'date' => '2026-09-10',
            'shift' => '09:00:00',
            'requested_by' => 'Budi',
        ]);

        $fakePdf = Mockery::mock(DomPdf::class);
        $fakePdf->shouldReceive('save')->once();
        Pdf::shouldReceive('loadView')
            ->once()
            ->with('pdf.berita_acara', ['schedule' => $schedule])
            ->andReturn($fakePdf);

        $url = app(BeritaAcaraPdfGenerator::class)->generate($schedule);

        $this->assertStringStartsWith(url('/') . '/beritaacara/', $url);
        $this->assertMatchesRegularExpression('~/beritaacara/berita_acara_\d+_\d+\.pdf$~', $url);
    }

    public function test_it_returns_null_when_pdf_generation_fails(): void
    {
        $schedule = ReservationSchedule::create([
            'date' => '2026-09-10',
            'shift' => '09:00:00',
            'requested_by' => 'Budi',
        ]);

        Pdf::shouldReceive('loadView')->once()->andThrow(new \RuntimeException('dompdf exploded'));

        $this->assertNull(app(BeritaAcaraPdfGenerator::class)->generate($schedule));
    }
}