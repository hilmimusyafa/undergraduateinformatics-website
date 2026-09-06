<?php

namespace Tests\Feature;

use App\Models\FeedbackLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesMicrosoftForms;
use Tests\TestCase;

class RefreshMsFormsDefinitionTest extends TestCase
{
    use FakesMicrosoftForms;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        FeedbackLink::query()->delete();
    }

    public function test_command_warms_the_cached_form_definition(): void
    {
        Http::fake($this->microsoftEndpoints());
        $feedbackLink = FeedbackLink::create(['link' => 'https://forms.office.com/r/abc123']);

        $this->artisan('msforms:refresh-definition')->assertSuccessful();

        $cacheKey = 'msforms-definition:' . md5($feedbackLink->link);

        $this->assertNotNull(Cache::get($cacheKey));
        $this->assertSame($feedbackLink->link, Cache::get($cacheKey)['link']);
    }

    public function test_command_succeeds_when_no_feedback_link_is_configured(): void
    {
        $this->artisan('msforms:refresh-definition')->assertSuccessful();
    }

    public function test_command_keeps_existing_cache_when_microsoft_is_unreachable(): void
    {
        Http::fake(['https://forms.office.com/r/*' => Http::response('', 500)]);
        $feedbackLink = FeedbackLink::create(['link' => 'https://forms.office.com/r/abc123']);

        $cacheKey = 'msforms-definition:' . md5($feedbackLink->link);
        Cache::put($cacheKey, ['link' => $feedbackLink->link, 'stale' => true]);

        $this->artisan('msforms:refresh-definition')->assertFailed();

        $this->assertSame(['link' => $feedbackLink->link, 'stale' => true], Cache::get($cacheKey));
    }
}