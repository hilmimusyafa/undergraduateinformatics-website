<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\Http;

trait FakesMicrosoftForms
{
    private string $runtimeFixture = 'form-definition.raw.json';

    private bool $microsoftUnreachable = false;

    private function microsoftEndpoints(): array
    {
        return [
            'https://forms.office.com/r/*' => Http::response('', 302, [
                'Location' => 'https://forms.office.com/pages/responsepage.aspx?id=FORM123',
            ]),
            'https://forms.cloud.microsoft/r/*' => Http::response('', 301, [
                'Location' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=FORM123&route=shorturl',
            ]),
            'https://forms.office.com/pages/responsepage.aspx*' => fn () => $this->responsePage(),
            'https://forms.cloud.microsoft/pages/responsepage.aspx*' => fn () => $this->responsePage(),
            'https://forms.cloud.microsoft/formapi/api/*/users/*/light/runtimeForms*' => fn () => Http::response(
                $this->runtimeDefinition(),
                200
            ),
            'https://forms.cloud.microsoft/formapi/api/*/users/*/forms(*)/responses' => Http::response('', 201),
        ];
    }

    private function responsePage()
    {
        if ($this->microsoftUnreachable) {
            return Http::response('', 500);
        }

        return Http::response(
            file_get_contents(base_path('tests/Fixtures/msforms/response-page.html')),
            200
        );
    }

    private function runtimeDefinition(): string
    {
        return (string) file_get_contents(base_path('tests/Fixtures/msforms/' . $this->runtimeFixture));
    }
}