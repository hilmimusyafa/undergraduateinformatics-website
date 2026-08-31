<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\FakesMicrosoftForms;
use Tests\TestCase;

class DumpMsFormsDefinitionTest extends TestCase
{
    use FakesMicrosoftForms;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Http::preventStrayRequests();
    }

    public function test_command_writes_the_raw_definition_to_storage(): void
    {
        Http::fake($this->microsoftEndpoints());

        $this->artisan('msforms:dump', ['link' => 'https://forms.office.com/r/abc123'])
            ->assertSuccessful();

        Storage::disk('local')->assertExists('msforms/raw-definition.json');

        $raw = json_decode(
            file_get_contents(base_path('tests/Fixtures/msforms/form-definition.raw.json')),
            true
        );

        $this->assertSame(
            json_encode($raw, JSON_PRETTY_PRINT),
            Storage::disk('local')->get('msforms/raw-definition.json')
        );
    }

    public function test_command_fails_when_microsoft_is_unreachable(): void
    {
        Http::fake(['https://forms.office.com/r/*' => Http::response('', 500)]);

        $this->artisan('msforms:dump', ['link' => 'https://forms.office.com/r/broken'])
            ->assertFailed()
            ->expectsOutputToContain('Unable to fetch the form definition from Microsoft Forms.');
    }
}