<?php

namespace Tests\Unit\Services;

use App\Services\MsForms\FormDefinitionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\FakesMicrosoftForms;
use Tests\TestCase;

class FormDefinitionServiceTest extends TestCase
{
    use FakesMicrosoftForms;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Http::fake($this->microsoftEndpoints());
        Cache::flush();
    }

    public function test_it_resolves_a_form_definition_from_a_plain_link_string(): void
    {
        $service = new FormDefinitionService();

        $payload = $service->resolve('https://forms.office.com/r/abc123');

        $this->assertSame('this is form title', $payload['title']['text']);
        $this->assertSame('https://forms.office.com/r/abc123', $payload['link']);
    }

    public function test_it_refreshes_and_caches_under_the_link_based_key(): void
    {
        $service = new FormDefinitionService();

        $payload = $service->refresh('https://forms.office.com/r/abc123');

        $this->assertSame('this is form title', $payload['title']['text']);
        $this->assertNotNull(Cache::get('msforms-definition:' . md5('https://forms.office.com/r/abc123')));
    }
}
