<?php

namespace Tests\Feature\Web;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_tags_returns_tag_list_with_post_counts(): void
    {
        Tag::create([
            'name' => 'Research',
            'description' => 'Research updates',
        ]);
        $academic = Tag::create([
            'name' => 'Academic',
            'description' => 'Academic announcements',
        ]);

        $post = Post::create([
            'title' => 'AI Lab Opening',
            'subtitle' => 'New computing resources available',
            'body' => '<p>Lab orientation next Monday.</p>',
            'image' => 'images/DummyImage.png',
        ]);
        $post->tags()->attach($academic);

        $response = $this->getJson('/api/tags');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'slug', 'name', 'description', 'posts_count'],
            ],
        ]);
        $response->assertJsonPath('data.0.name', 'Academic');
        $response->assertJsonPath('data.0.posts_count', 1);
        $response->assertJsonPath('data.1.name', 'Research');
        $response->assertJsonPath('data.1.posts_count', 0);
    }

    public function test_api_tags_includes_tag_without_posts(): void
    {
        Tag::create([
            'name' => 'Beasiswa',
            'description' => 'Scholarship information',
        ]);

        $response = $this->getJson('/api/tags');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Beasiswa');
        $response->assertJsonPath('data.0.posts_count', 0);
    }

    public function test_api_tags_returns_empty_data_when_no_tags(): void
    {
        $response = $this->getJson('/api/tags');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('data', []);
    }

    public function test_api_tags_orders_by_most_recent_post_update(): void
    {
        $older = Tag::create([
            'name' => 'Older',
            'description' => null,
        ]);
        $newer = Tag::create([
            'name' => 'Newer',
            'description' => null,
        ]);

        $olderPost = Post::create([
            'title' => 'Old Announcement',
            'subtitle' => 'Old subtitle',
            'body' => '<p>Old content.</p>',
            'image' => 'images/DummyImage.png',
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDays(10),
        ]);
        $older->posts()->attach($olderPost);

        $newerPost = Post::create([
            'title' => 'Fresh Announcement',
            'subtitle' => 'Fresh subtitle',
            'body' => '<p>Fresh content.</p>',
            'image' => 'images/DummyImage.png',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDay(),
        ]);
        $newer->posts()->attach($newerPost);

        $response = $this->getJson('/api/tags');

        $response->assertJsonPath('data.0.name', 'Newer');
        $response->assertJsonPath('data.1.name', 'Older');
    }

    public function test_api_tags_places_tags_without_posts_last(): void
    {
        $empty = Tag::create([
            'name' => 'Empty',
            'description' => null,
        ]);
        $fresh = Tag::create([
            'name' => 'Fresh',
            'description' => null,
        ]);

        $post = Post::create([
            'title' => 'New Post',
            'subtitle' => 'New subtitle',
            'body' => '<p>New content.</p>',
            'image' => 'images/DummyImage.png',
        ]);
        $fresh->posts()->attach($post);

        $response = $this->getJson('/api/tags');

        $response->assertJsonPath('data.0.name', 'Fresh');
        $response->assertJsonPath('data.1.name', 'Empty');
    }

    public function test_web_tags_route_renders_app_wrapper_with_initial_data_and_seo_tags(): void
    {
        Tag::create([
            'name' => 'Academic',
            'description' => 'Academic announcements',
        ]);

        $response = $this->get('/tags');

        $response->assertStatus(200);
        $response->assertViewIs('app');
        $response->assertViewHas('initialData');
        $response->assertSee('__INITIAL_DATA__');
        $response->assertSee('application/ld+json');
        $response->assertSee('CollectionPage');

        preg_match('/window\.__INITIAL_DATA__ = (\{.*?\});/s', $response->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Initial data script tag not found');
        $initialData = json_decode($matches[1], true);
        $this->assertSame('success', $initialData['status']);
        $this->assertSame('Academic', $initialData['data'][0]['name']);
        $this->assertSame(0, $initialData['data'][0]['posts_count']);
    }

    public function test_web_tag_detail_route_renders_by_slug(): void
    {
        $tag = Tag::create([
            'name' => 'Beasiswa',
            'description' => 'Info beasiswa',
        ]);

        $response = $this->get('/tags/beasiswa');

        $response->assertStatus(200);
        $response->assertViewIs('TagPage');
        $response->assertSee('Beasiswa');
    }

    public function test_web_tag_detail_route_resolves_by_id_for_backward_compatibility(): void
    {
        $tag = Tag::create([
            'name' => 'Beasiswa',
            'description' => 'Info beasiswa',
        ]);

        $response = $this->get('/tags/' . $tag->id);

        $response->assertStatus(200);
        $response->assertSee('Beasiswa');
    }

    public function test_web_tag_detail_route_returns_404_for_missing_slug(): void
    {
        $response = $this->get('/tags/tidak-ada');

        $response->assertStatus(404);
    }
}
