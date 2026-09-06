<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_to_admin_dashboard(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'password_recovery_id' => 1,
        ]);

        $response = $this->post('/admin/login', ['email' => 'admin@example.com', 'password' => 'password']);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $response = $this->post('/admin/login', ['email' => 'nobody@example.com', 'password' => 'wrong']);

        $response->assertSessionHas('error');
    }
}