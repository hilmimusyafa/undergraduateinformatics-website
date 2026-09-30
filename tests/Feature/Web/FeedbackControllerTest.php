<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesMicrosoftForms;
use Tests\TestCase;

class FeedbackControllerTest extends TestCase
{
    use FakesMicrosoftForms;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Http::fake($this->microsoftEndpoints());
    }

    public function test_feedback_page_renders_blade_view_successfully(): void
    {
        $response = $this->get('/feedback');

        $response->assertStatus(200);
        $response->assertViewIs('FeedbackPage');
        $response->assertSee('Feedback - Website Informasi S1 Informatika');
        $response->assertSee('Feedback Program Studi');
    }
}
