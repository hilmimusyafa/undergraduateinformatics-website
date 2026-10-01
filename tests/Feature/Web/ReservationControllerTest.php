<?php

namespace Tests\Feature\Web;

use App\Models\ReservationLink;
use App\Services\MsForms\FormDefinitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesMicrosoftForms;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use FakesMicrosoftForms;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->runtimeFixture = 'reservation-form.raw.json';

        Http::preventStrayRequests();
        Http::fake($this->microsoftEndpoints());
    }

    public function test_reservation_page_renders_blade_view_successfully(): void
    {
        $response = $this->get('/reservation');

        $response->assertStatus(200);
        $response->assertViewIs('ReservationPage');
        $response->assertSee('Reservasi Pertemuan dengan Prodi');
    }

    public function test_reservation_page_displays_configured_iframe(): void
    {
        ReservationLink::create(['link' => 'https://forms.office.com/r/abc123']);

        $response = $this->get('/reservation');

        $response->assertStatus(200);
        $response->assertSee('https://forms.office.com/r/abc123');
    }

    public function test_reservation_page_displays_schedule_information_above_form(): void
    {
        $response = $this->get('/reservation');

        $response->assertStatus(200);
        $response->assertSee('Informasi Jadwal & Agenda Pertemuan', false);
        $response->assertSee('Hari Pelayanan');
        $response->assertSee('Pilihan Sesi Pertemuan');
        $response->assertSee('Status Ketersediaan Slot');
        $response->assertSee('Formulir Pengajuan Reservasi');
    }

    public function test_reservation_page_displays_agenda_cards_and_modal_details_when_schedules_exist(): void
    {
        $schedule = \App\Models\ReservationSchedule::create([
            'date' => '2026-10-12',
            'shift' => '09:00:00',
            'requested_by' => 'Ketua HIMA IF',
            'study_program' => 'S1 Informatika',
            'meeting_room' => 'Ruang Rapat Prodi 101',
            'participants' => '6 orang',
            'agenda' => 'Diskusi Koordinasi Program Magang Industri',
            'city' => 'Bandung',
        ]);

        $response = $this->get('/reservation');

        $response->assertStatus(200);
        $response->assertSee('Diskusi Koordinasi Program Magang Industri');
        $response->assertSee('Ketua HIMA IF');
        $response->assertSee('Ruang Rapat Prodi 101');
        $response->assertSee('agendaDetailModal-' . $schedule->id);
        $response->assertSee('Detail Informasi Agenda Pertemuan');
    }

    public function test_reservation_page_displays_calendar_component(): void
    {
        $response = $this->get('/reservation');

        $response->assertStatus(200);
        $response->assertSee('reservationCalendarSection');
        $response->assertSee('calendarGridBody');
        $response->assertSee('calMonthTitle');
        $response->assertSee('calendarSelectedPanel');
    }
}