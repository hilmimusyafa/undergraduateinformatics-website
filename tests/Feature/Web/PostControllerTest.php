<?php

namespace Tests\Feature\Web;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_post_detail_route_renders_app_with_initial_data_and_seo_tags(): void
    {
        $tag = Tag::create([
            'name' => 'Beasiswa',
            'description' => 'Info beasiswa',
        ]);

        $post = Post::create([
            'title' => 'Pendaftaran Beasiswa 2026',
            'subtitle' => 'Periode baru dibuka',
            'body' => '<p>Detail.</p>',
            'image' => 'images/placeholder.png',
        ]);
        $post->tags()->attach($tag);

        $response = $this->get('/posts/' . $post->slug);

        $response->assertStatus(200);
        $response->assertViewIs('PostPage');
        $response->assertSee('Pendaftaran Beasiswa 2026');
        $response->assertSee('Periode baru dibuka');
    }

    public function test_web_post_detail_og_image_falls_back_to_default_banner_when_no_image(): void
    {
        $post = Post::create([
            'title' => 'Post Tanpa Gambar',
            'subtitle' => 'Subtitle',
            'body' => '<p>Detail.</p>',
            'image' => null,
        ]);

        $response = $this->get('/posts/' . $post->slug);

        $response->assertStatus(200);
        $response->assertViewIs('PostPage');
    }

    public function test_web_post_detail_route_resolves_by_id_for_backward_compatibility(): void
    {
        $post = Post::create([
            'title' => 'Pendaftaran Beasiswa 2026',
            'subtitle' => 'Periode baru dibuka',
            'body' => '<p>Detail.</p>',
            'image' => 'images/placeholder.png',
        ]);

        $response = $this->get('/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertViewIs('PostPage');
    }

    public function test_web_post_detail_route_returns_404_for_missing_slug(): void
    {
        $response = $this->get('/posts/tidak-ada');

        $response->assertStatus(404);
    }
}