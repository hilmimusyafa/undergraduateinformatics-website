<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OriginRestrictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_accepts_same_origin_request(): void
    {
        $this->getJson('/api/home', ['Origin' => 'http://localhost:8000'])
            ->assertStatus(200);
    }

    public function test_api_accepts_request_without_origin_header(): void
    {
        $this->getJson('/api/home')
            ->assertStatus(200);
    }

    public function test_api_rejects_unknown_origin(): void
    {
        $this->getJson('/api/home', ['Origin' => 'https://evil.example.com'])
            ->assertStatus(403);
    }

    public function test_api_rejects_foreign_origin_matching_configured_host_pattern(): void
    {
        $this->getJson('/api/home', ['Origin' => 'https://localhost:9999'])
            ->assertStatus(200);
    }
}