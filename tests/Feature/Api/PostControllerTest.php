<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_post_detail_returns_post_json_structure(): void
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

        $response = $this->getJson('/api/posts/' . $post->slug);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonStructure([
            'status',
            'data' => [
                'id',
                'slug',
                'title',
                'subtitle',
                'body',
                'image',
                'created_at',
                'updated_at',
                'tags' => ['*' => ['id', 'slug', 'name']],
            ],
        ]);
        $response->assertJsonPath('data.slug', $post->slug);
        $response->assertJsonPath('data.title', 'Pendaftaran Beasiswa 2026');
        $response->assertJsonPath('data.tags.0.slug', 'beasiswa');
        $response->assertJsonPath('data.created_at', $post->created_at->toIso8601String());
        $this->assertArrayNotHasKey('description', $response->json('data.tags.0'));
    }

    public function test_api_post_detail_resolves_by_numeric_id(): void
    {
        $post = Post::create([
            'title' => 'Pendaftaran Beasiswa 2026',
            'subtitle' => 'Periode baru dibuka',
            'body' => '<p>Detail.</p>',
            'image' => 'images/placeholder.png',
        ]);

        $response = $this->getJson('/api/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('data.slug', $post->slug);
    }

    public function test_api_post_detail_returns_404_for_unknown_slug(): void
    {
        $response = $this->getJson('/api/posts/tidak-ada');

        $response->assertStatus(404);
        $response->assertJsonPath('status', 'error');
        $response->assertJsonPath('message', 'Post not found');
    }
}