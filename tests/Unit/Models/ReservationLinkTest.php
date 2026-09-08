<?php

namespace Tests\Unit\Models;

use App\Models\ReservationLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_configured_scope_only_returns_links_with_a_value(): void
    {
        ReservationLink::query()->delete();

        ReservationLink::create(['link' => 'https://forms.office.com/r/abc123']);
        ReservationLink::create(['link' => '']);

        $configured = ReservationLink::configured()->get();

        $this->assertCount(1, $configured);
        $this->assertSame('https://forms.office.com/r/abc123', $configured->first()->link);
    }
}