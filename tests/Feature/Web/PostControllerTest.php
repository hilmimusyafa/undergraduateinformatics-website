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
        $response->assertViewIs('app');
        $response->assertViewHas('initialData');
        $response->assertSee('__INITIAL_DATA__');
        $response->assertSee('application/ld+json');
        $response->assertSee('Article');
        $response->assertSee('Pendaftaran Beasiswa 2026 - Portal Informasi Sarjana Informatika', false);

        preg_match('/window\.__INITIAL_DATA__ = (\{.*?\});/s', $response->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Initial data script tag not found');
        $initialData = json_decode($matches[1], true);
        $this->assertSame('success', $initialData['status']);
        $this->assertSame('Pendaftaran Beasiswa 2026', $initialData['data']['title']);
        $this->assertSame('beasiswa', $initialData['data']['tags'][0]['slug']);
        $this->assertSame($post->created_at->toIso8601String(), $initialData['data']['created_at']);

        preg_match('/<script type="application\/ld\+json">\s*(\{.*?\})\s*<\/script>/s', $response->getContent(), $ldMatches);
        $this->assertNotEmpty($ldMatches, 'JSON-LD script tag not found');
        $jsonLd = json_decode($ldMatches[1], true);
        $this->assertSame('Article', $jsonLd['@type']);
        $this->assertSame($post->created_at->toIso8601String(), $jsonLd['datePublished']);
        $this->assertSame($post->updated_at->toIso8601String(), $jsonLd['dateModified']);
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
        $response->assertViewIs('app');
        $response->assertSee('<meta data-ssr="true" property="og:image" content="' . url('/images/banner.jpg') . '">', false);
        $response->assertSee('"image":null', false);
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
        $response->assertViewIs('app');
    }

    public function test_web_post_detail_route_returns_404_for_missing_slug(): void
    {
        $response = $this->get('/posts/tidak-ada');

        $response->assertStatus(404);
        $response->assertViewIs('app');
        $response->assertSee('window.__INITIAL_DATA__ = {"notFound":true};', false);
    }

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
