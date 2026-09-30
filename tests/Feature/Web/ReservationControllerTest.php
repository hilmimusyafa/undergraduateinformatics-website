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
}