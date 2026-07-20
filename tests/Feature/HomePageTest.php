<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\ImportantSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_home_route_renders_app_wrapper_with_initial_data_and_seo_tags(): void
    {
        $tag = Tag::create([
            'name' => 'Academic',
            'description' => 'Academic announcements'
        ]);

        $post = Post::create([
            'title' => 'Welcome to Informatics',
            'subtitle' => 'New semester starting soon',
            'body' => '<p>Check your schedule.</p>',
            'image' => 'images/DummyImage.png'
        ]);
        $post->tags()->attach($tag);

        $section = ImportantSection::create([
            'name' => 'Student Affairs',
            'order_number' => 1
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('app');
        $response->assertViewHas('initialData');
        $response->assertSee('__INITIAL_DATA__');
        $response->assertSee('Program Studi S1 Informatika');
        $response->assertSee('Welcome to Informatics');
        $response->assertSee('Academic');
    }

    public function test_api_home_route_returns_identical_json_structure(): void
    {
        $tag = Tag::create([
            'name' => 'Research',
            'description' => 'Research updates'
        ]);

        $post = Post::create([
            'title' => 'AI Lab Opening',
            'subtitle' => 'New computing resources available',
            'body' => '<p>Lab orientation next Monday.</p>',
            'image' => 'images/DummyImage.png'
        ]);
        $post->tags()->attach($tag);

        $section = ImportantSection::create([
            'name' => 'Curriculum',
            'order_number' => 1
        ]);

        $response = $this->getJson('/api/home');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'tags' => [
                '*' => ['id', 'name', 'description']
            ],
            'posts' => [
                '*' => ['id', 'title', 'subtitle', 'body', 'image', 'tags']
            ],
            'sections' => [
                '*' => ['id', 'name', 'order_number']
            ]
        ]);
        $response->assertJsonPath('posts.0.title', 'AI Lab Opening');
        $response->assertJsonPath('tags.0.name', 'Research');
        $response->assertJsonPath('sections.0.name', 'Curriculum');
    }
}
