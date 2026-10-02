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

    public function test_authenticated_user_visiting_login_is_redirected_to_admin_dashboard(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'password_recovery_id' => 1,
        ]);

        $response = $this->actingAs(User::first())->get('/admin/login');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_page_displays_back_to_home_link(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Kembali ke Halaman Utama');
        $response->assertSee(route('home'));
    }

    public function test_login_page_displays_error_alert_with_structured_content(): void
    {
        $response = $this->withSession(['error' => 'Email atau password salah, silahkan coba lagi'])
            ->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Email atau password salah, silahkan coba lagi');
        $response->assertSee('admin-toast');
        $response->assertSee('admin-toast__text');
    }
}