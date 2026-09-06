<?php

namespace Tests\Feature\Web;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_post_detail_route_resolves_by_slug(): void
    {
        $post = Post::create([
            'title' => 'Pendaftaran Beasiswa 2026',
            'subtitle' => 'Periode baru dibuka',
            'body' => '<p>Detail.</p>',
            'image' => 'images/placeholder.png',
        ]);

        $response = $this->get('/posts/' . $post->slug);

        $response->assertStatus(200);
        $response->assertViewIs('PostPage');
        $response->assertSee($post->title, false);
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
        $response->assertSee($post->title, false);
    }

    public function test_web_post_detail_route_renders_post_with_tags(): void
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
        $response->assertSee($post->title, false);
        $response->assertSee($tag->name, false);
    }

    public function test_web_post_detail_route_returns_404_for_unknown_slug(): void
    {
        $response = $this->get('/posts/tidak-ada');

        $response->assertStatus(404);
    }
}
