<?php

namespace Tests\Feature\Web;

use App\Http\Controllers\FeedbackController;
use App\Models\FeedbackLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Tests\TestCase;

class AdminFeedbackControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        FeedbackLink::query()->delete();
    }

    private function createAdminUser(): User
    {
        return User::create([
            'email' => fake()->unique()->safeEmail(),
            'password_recovery_id' => 1,
            'password' => bcrypt('password'),
        ]);
    }

    public function test_admin_feedback_index_returns_list_view(): void
    {
        FeedbackLink::create(['link' => 'https://example.com/forms']);

        $view = (new FeedbackController())->index();

        $this->assertInstanceOf(View::class, $view);
        $this->assertSame('AdminFeedback.AdminPageFeedback', $view->name());
        $this->assertSame('https://example.com/forms', $view->getData()['feedbackLink']->link);
    }

    public function test_admin_feedback_edit_returns_form_view(): void
    {
        FeedbackLink::create(['link' => 'https://example.com/forms']);

        $view = (new FeedbackController())->edit();

        $this->assertInstanceOf(View::class, $view);
        $this->assertSame('AdminFeedback.AdminPageEditFeedback', $view->name());
        $this->assertSame('https://example.com/forms', $view->getData()['feedbackLink']->link);
    }

    public function test_admin_feedback_update_changes_link(): void
    {
        $link = FeedbackLink::create(['link' => 'https://example.com/old']);
        Http::fake(['https://example.com/new' => Http::response('', 200)]);
        $this->actingAs($this->createAdminUser());

        $response = $this->put('/admin/feedback/1', ['new_feedback_link' => 'https://example.com/new']);

        $response->assertRedirect(route('feedback.index'));
        $this->assertSame('https://example.com/new', $link->fresh()->link);
    }

    public function test_admin_feedback_update_rejects_invalid_url(): void
    {
        $link = FeedbackLink::create(['link' => 'https://example.com/old']);
        $this->actingAs($this->createAdminUser());

        $response = $this->put('/admin/feedback/1', ['new_feedback_link' => 'not-a-url']);

        $response->assertSessionHasErrors('new_feedback_link');
        $this->assertSame('https://example.com/old', $link->fresh()->link);
    }
}
