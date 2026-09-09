<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createPost(string $title, string $subtitle, string $body, ?\DateTimeInterface $updatedAt = null): Post
    {
        return Post::create([
            'title' => $title,
            'subtitle' => $subtitle,
            'body' => $body,
            'image' => 'images/placeholder.png',
            'updated_at' => $updatedAt,
            'created_at' => $updatedAt,
        ]);
    }

    public function test_api_search_matches_title(): void
    {
        $post = $this->createPost('Pendaftaran Beasiswa 2026', 'Periode baru dibuka', '<p>Detail.</p>');

        $response = $this->getJson('/api/posts/search?q=beasiswa');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.title', 'Pendaftaran Beasiswa 2026');
        $response->assertJsonPath('data.0.slug', $post->slug);
    }

    public function test_api_search_matches_subtitle(): void
    {
        $this->createPost('Pendaftaran Beasiswa 2026', 'Periode baru dibuka', '<p>Detail.</p>');

        $response = $this->getJson('/api/posts/search?q=periode');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'Pendaftaran Beasiswa 2026');
    }

    public function test_api_search_matches_body(): void
    {
        $this->createPost('Pendaftaran Beasiswa 2026', 'Periode baru dibuka', '<p>Jadwal seleksi Mei.</p>');

        $response = $this->getJson('/api/posts/search?q=seleksi');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'Pendaftaran Beasiswa 2026');
    }

    public function test_api_search_returns_empty_when_no_match(): void
    {
        $this->createPost('Pendaftaran Beasiswa 2026', 'Periode baru dibuka', '<p>Detail.</p>');

        $response = $this->getJson('/api/posts/search?q=tidakada');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('data', []);
        $response->assertJsonPath('meta.total', 0);
    }

    public function test_api_search_with_empty_q_returns_all_posts_ordered_by_updated_at_desc(): void
    {
        $this->createPost('Pengumuman Lama', 'Subtitle lama', '<p>Konten lama.</p>', now()->subDays(5));
        $this->createPost('Pengumuman Baru', 'Subtitle baru', '<p>Konten baru.</p>', now()->subDay());

        $response = $this->getJson('/api/posts/search');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.title', 'Pengumuman Baru');
        $response->assertJsonPath('data.1.title', 'Pengumuman Lama');
        $response->assertJsonPath('meta.total', 2);
    }

    public function test_api_search_escapes_like_wildcards(): void
    {
        $this->createPost('Diskon 100%', 'Promo terbatas', '<p>Konten.</p>', now()->subDay());
        $this->createPost('Diskon 100', 'Promo terbatas', '<p>Konten.</p>', now()->subHours(2));

        $response = $this->getJson('/api/posts/search?q=100%');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.title', 'Diskon 100%');
    }

    public function test_api_search_percent_only_returns_empty(): void
    {
        $this->createPost('Beasiswa', 'Periode baru dibuka', '<p>Detail.</p>');

        $response = $this->getJson('/api/posts/search?q=%');

        $response->assertStatus(200);
        $response->assertJsonPath('data', []);
        $response->assertJsonPath('meta.total', 0);
    }

    public function test_api_search_with_present_empty_q_returns_all_posts(): void
    {
        $this->createPost('Pengumuman Lama', 'Subtitle lama', '<p>Konten lama.</p>', now()->subDays(5));
        $this->createPost('Pengumuman Baru', 'Subtitle baru', '<p>Konten baru.</p>', now()->subDay());

        $response = $this->getJson('/api/posts/search?q=');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.title', 'Pengumuman Baru');
        $response->assertJsonPath('data.1.title', 'Pengumuman Lama');
        $response->assertJsonPath('meta.total', 2);
    }

    public function test_api_search_paginates_results(): void
    {
        foreach (range(1, 5) as $i) {
            $this->createPost("Pengumuman ke-$i", "Subtitle $i", '<p>Konten.</p>', now()->subMinutes(5 - $i));
        }

        $response = $this->getJson('/api/posts/search?per_page=2&page=2');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.title', 'Pengumuman ke-3');
        $response->assertJsonPath('data.1.title', 'Pengumuman ke-2');
        $response->assertJsonPath('meta.current_page', 2);
        $response->assertJsonPath('meta.per_page', 2);
        $response->assertJsonPath('meta.total', 5);
        $response->assertJsonPath('meta.last_page', 3);
    }

    public function test_api_search_normalizes_page_and_per_page(): void
    {
        $this->createPost('Pengumuman', 'Subtitle', '<p>Konten.</p>', now()->subDay());
        $this->createPost('Pengumuman 2', 'Subtitle 2', '<p>Konten.</p>', now()->subHours(2));

        $response = $this->getJson('/api/posts/search?page=0&per_page=999');

        $response->assertStatus(200);
        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.per_page', 50);
        $response->assertJsonCount(2, 'data');
    }

    public function test_api_search_returns_summary_shape_without_body(): void
    {
        $tag = Tag::create(['name' => 'Beasiswa', 'description' => 'Info beasiswa']);
        $post = $this->createPost('Pendaftaran Beasiswa 2026', 'Periode baru dibuka', '<p>Detail.</p>');
        $post->tags()->attach($tag);

        $response = $this->getJson('/api/posts/search?q=beasiswa');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id',
                    'slug',
                    'title',
                    'subtitle',
                    'updated_at',
                    'tags' => ['*' => ['id', 'slug', 'name']],
                ],
            ],
            'meta' => ['current_page', 'per_page', 'total', 'last_page'],
        ]);
        $this->assertArrayNotHasKey('body', $response->json('data.0'));
        $this->assertArrayNotHasKey('image', $response->json('data.0'));
        $response->assertJsonPath('data.0.tags.0.slug', 'beasiswa');
    }

    public function test_api_search_uses_single_character_escape_clause(): void
    {
        $this->createPost('Pendaftaran Beasiswa 2026', 'Periode baru dibuka', '<p>Detail.</p>');

        DB::enableQueryLog();
        $this->getJson('/api/posts/search?q=beasiswa');
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertNotEmpty($queries);
        $likeQuery = collect($queries)->first(fn (array $query) => str_contains($query['query'], 'ESCAPE'));
        $this->assertNotNull($likeQuery, 'A LIKE ... ESCAPE query should run');
        $this->assertStringContainsString("ESCAPE '!'", $likeQuery['query']);
        $this->assertStringNotContainsString("ESCAPE '\\'", $likeQuery['query']);
    }

    public function test_web_search_route_renders_app_with_initial_data_and_is_not_shadowed(): void
    {
        $this->createPost('Pendaftaran Beasiswa 2026', 'Periode baru dibuka', '<p>Detail.</p>');

        $response = $this->get('/posts/search?q=beasiswa');

        $response->assertStatus(200);
        $response->assertViewIs('app');
        $response->assertViewHas('initialData');
        $response->assertSee('__INITIAL_DATA__');
        $response->assertSee('application/ld+json');
        $response->assertSee('SearchResultsPage');
        $response->assertSee('Cari Informasi - Portal Informasi Sarjana Informatika', false);

        preg_match('/window\.__INITIAL_DATA__ = (\{.*?\});/s', $response->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Initial data script tag not found');
        $initialData = json_decode($matches[1], true);
        $this->assertSame('success', $initialData['status']);
        $this->assertSame('Pendaftaran Beasiswa 2026', $initialData['data'][0]['title']);
        $this->assertSame(1, $initialData['meta']['total']);
        $this->assertArrayNotHasKey('body', $initialData['data'][0]);
        $this->assertArrayNotHasKey('image', $initialData['data'][0]);
    }
}
