<?php

namespace Tests\Feature\Web;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_tags_route_renders_app_wrapper_with_initial_data_and_seo_tags(): void
    {
        Tag::create([
            'name' => 'Academic',
            'description' => 'Academic announcements',
        ]);

        $response = $this->get('/tags');

        $response->assertStatus(200);
        $response->assertViewIs('TagPage');
        $response->assertSee('Academic');
        $response->assertSee('Daftar Kategori');
    }

    public function test_web_tag_detail_route_renders_app_with_initial_data_and_seo_tags(): void
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
        $tag->posts()->attach($post);

        $response = $this->get('/tags/beasiswa');

        $response->assertStatus(200);
        $response->assertViewIs('TagPage');
        $response->assertSee('Beasiswa');
        $response->assertSee('Pendaftaran Beasiswa 2026');
    }

    public function test_web_tag_detail_route_resolves_by_id_for_backward_compatibility(): void
    {
        $tag = Tag::create([
            'name' => 'Beasiswa',
            'description' => 'Info beasiswa',
        ]);

        $response = $this->get('/tags/' . $tag->id);

        $response->assertStatus(200);
        $response->assertViewIs('TagPage');
        $response->assertSee('Beasiswa');
    }

    public function test_web_tag_detail_route_returns_404_for_missing_slug(): void
    {
        $response = $this->get('/tags/tidak-ada');

        $response->assertStatus(404);
    }
}