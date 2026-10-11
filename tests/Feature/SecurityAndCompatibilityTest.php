<?php

namespace Tests\Feature;

use App\Models\PasswordRecovery;
use App\Models\Post;
use App\Models\ReservationLink;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityAndCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_post_route_redirects_with_301_to_slug(): void
    {
        $post = Post::create([
            'title' => 'Judul Artikel Lama',
            'subtitle' => 'Sub judul',
            'body' => '<p>Konten artikel</p>',
        ]);

        $response = $this->get('/post/' . $post->id);

        $response->assertStatus(301);
        $response->assertRedirect(route('posts.show', ['slug' => $post->slug]));
    }

    public function test_legacy_post_route_returns_404_for_nonexistent_id(): void
    {
        $response = $this->get('/post/99999');

        $response->assertStatus(404);
    }

    public function test_legacy_tag_route_redirects_with_301_to_slug(): void
    {
        $tag = Tag::create([
            'name' => 'Kategori Lama',
            'description' => 'Deskripsi kategori',
        ]);

        $response = $this->get('/tag/' . $tag->id);

        $response->assertStatus(301);
        $response->assertRedirect(route('tags.show', ['slug' => $tag->slug]));
    }

    public function test_legacy_tag_route_returns_404_for_nonexistent_id(): void
    {
        $response = $this->get('/tag/99999');

        $response->assertStatus(404);
    }

    public function test_web_password_recovery_cannot_be_bypassed_without_answering_questions(): void
    {
        $user = User::create([
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => Hash::make('password123'),
            'password_recovery_id' => 1,
        ]);

        PasswordRecovery::create([
            'user_id' => $user->id,
            'first_question' => 'Kota lahir?',
            'second_question' => 'Nama hewan?',
            'first_answer' => 'bandung',
            'second_answer' => 'kucing',
        ]);

        // Attempting to directly post password without answer verification must fail
        $response = $this->post(route('admin.submitPasswordRecovery'), [
            'user_id' => $user->id,
            'new_password' => 'newsecretpassword',
            'confirm_new_password' => 'newsecretpassword',
        ]);

        $response->assertRedirect(route('admin.forgotPassword'));
        $response->assertSessionHas('error');

        // Verify password in DB was NOT changed
        $user->refresh();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_web_password_recovery_succeeds_when_legitimate_flow_followed(): void
    {
        $user = User::create([
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => Hash::make('password123'),
            'password_recovery_id' => 1,
        ]);

        PasswordRecovery::create([
            'user_id' => $user->id,
            'first_question' => 'Kota lahir?',
            'second_question' => 'Nama hewan?',
            'first_answer' => 'bandung',
            'second_answer' => 'kucing',
        ]);

        // Step 1: Submit answers
        $answerResponse = $this->post(route('admin.submitAnswerRecovery'), [
            'user_id' => $user->id,
            'first_answer' => 'Bandung',
            'second_answer' => 'Kucing',
        ]);

        $answerResponse->assertRedirect(route('admin.passwordRecoveryForm'));
        $this->assertEquals($user->id, session('password_reset_verified_user_id'));

        // Step 2: Submit new password with verified session
        $passwordResponse = $this->post(route('admin.submitPasswordRecovery'), [
            'user_id' => $user->id,
            'new_password' => 'newsecretpassword',
            'confirm_new_password' => 'newsecretpassword',
        ]);

        $passwordResponse->assertRedirect(route('admin.login'));
        $passwordResponse->assertSessionHas('success');

        // Verify password in DB WAS updated
        $user->refresh();
        $this->assertTrue(Hash::check('newsecretpassword', $user->password));
        $this->assertNull(session('password_reset_verified_user_id'));
    }

    public function test_api_password_recovery_rejects_unauthenticated_put_without_reset_token(): void
    {
        $user = User::create([
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => Hash::make('oldpassword'),
            'password_recovery_id' => 1,
        ]);

        $response = $this->putJson('/api/passwordRecovery/' . $user->id, [
            'reset_token' => 'invalid_or_missing_token',
            'new_password' => 'hackedpassword',
            'confirm_new_password' => 'hackedpassword',
        ]);

        $response->assertStatus(403);

        $user->refresh();
        $this->assertTrue(Hash::check('oldpassword', $user->password));
    }

    public function test_api_password_recovery_flow_with_valid_token_succeeds(): void
    {
        $user = User::create([
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => Hash::make('oldpassword'),
            'password_recovery_id' => 1,
        ]);

        PasswordRecovery::create([
            'user_id' => $user->id,
            'first_question' => 'Kota lahir?',
            'second_question' => 'Nama hewan?',
            'first_answer' => 'bandung',
            'second_answer' => 'kucing',
        ]);

        // Answer questions to get reset token
        $answerResponse = $this->postJson('/api/passwordRecovery', [
            'user_id' => $user->id,
            'first_answer' => 'bandung',
            'second_answer' => 'kucing',
        ]);

        $answerResponse->assertStatus(200);
        $resetToken = $answerResponse->json('reset_token');
        $this->assertNotEmpty($resetToken);

        // Update password with token
        $updateResponse = $this->putJson('/api/passwordRecovery/' . $user->id, [
            'reset_token' => $resetToken,
            'new_password' => 'brandnewpassword',
            'confirm_new_password' => 'brandnewpassword',
        ]);

        $updateResponse->assertStatus(200);

        $user->refresh();
        $this->assertTrue(Hash::check('brandnewpassword', $user->password));
    }

    public function test_user_password_hash_is_hidden_from_json_serialization(): void
    {
        $user = User::create([
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => Hash::make('secretpassword'),
            'password_recovery_id' => 1,
        ]);

        $serialized = $user->toArray();

        $this->assertArrayNotHasKey('password', $serialized);
        $this->assertArrayNotHasKey('remember_token', $serialized);
    }

    public function test_post_body_is_sanitized_against_xss_attacks(): void
    {
        $maliciousBody = '<p>Normal paragraph</p><script>alert("XSS")</script><img src="x" onerror="alert(1)">';

        $post = Post::create([
            'title' => 'Security Test Post',
            'subtitle' => 'Testing XSS sanitization',
            'body' => $maliciousBody,
        ]);

        $this->assertStringNotContainsString('<script>', $post->body);
        $this->assertStringNotContainsString('onerror', $post->body);
        $this->assertStringContainsString('<p>Normal paragraph</p>', $post->body);
    }

    public function test_reservation_link_api_requires_authentication(): void
    {
        ReservationLink::create(['link' => 'https://forms.office.com/r/test']);

        $response = $this->deleteJson('/api/reservation/link/1');

        $response->assertStatus(401);
    }
}
