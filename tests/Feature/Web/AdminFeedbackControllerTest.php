<?php

namespace Tests\Feature\Web;

use App\Models\FeedbackLink;
use App\Models\ReservationLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminFeedbackControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        FeedbackLink::query()->delete();
        ReservationLink::query()->delete();
    }

    private function createAdminUser(): User
    {
        return User::create([
            'email' => fake()->unique()->safeEmail(),
            'password_recovery_id' => 1,
            'password' => bcrypt('password'),
        ]);
    }

    public function test_form_link_page_requires_authentication(): void
    {
        $this->get('/admin/form-link')->assertRedirect(route('admin.login'));
    }

    public function test_form_link_page_renders_both_links(): void
    {
        FeedbackLink::create(['link' => 'https://example.com/forms']);
        ReservationLink::create(['link' => 'https://example.com/reservation']);

        $response = $this->actingAs($this->createAdminUser())->get('/admin/form-link');

        $response->assertStatus(200);
        $response->assertViewIs('AdminDashboard.feedback');
        $response->assertSee('Manajemen Form Link');
        $response->assertSee('https://example.com/forms');
        $response->assertSee('https://example.com/reservation');
    }

    public function test_form_link_update_changes_feedback_link(): void
    {
        $link = FeedbackLink::create(['link' => 'https://example.com/old']);
        Http::fake(['https://example.com/new' => Http::response('', 200)]);
        $this->actingAs($this->createAdminUser());

        $response = $this->put('/admin/form-link/feedback', [
            'feedback_link' => 'https://example.com/new',
        ]);

        $response->assertRedirect(route('admin.form-link'));
        $this->assertSame('https://example.com/new', $link->fresh()->link);
    }

    public function test_form_link_update_rejects_invalid_feedback_url(): void
    {
        $link = FeedbackLink::create(['link' => 'https://example.com/old']);
        $this->actingAs($this->createAdminUser());

        $response = $this->put('/admin/form-link/feedback', [
            'feedback_link' => 'not-a-url',
        ]);

        $response->assertSessionHasErrors('feedback_link');
        $this->assertSame('https://example.com/old', $link->fresh()->link);
    }

    public function test_form_link_update_changes_reservation_link(): void
    {
        $link = ReservationLink::create(['link' => 'https://example.com/old']);
        Http::fake(['https://example.com/new' => Http::response('', 200)]);
        $this->actingAs($this->createAdminUser());

        $response = $this->put('/admin/form-link/reservation', [
            'reservation_link' => 'https://example.com/new',
        ]);

        $response->assertRedirect(route('admin.form-link'));
        $this->assertSame('https://example.com/new', $link->fresh()->link);
    }
}
