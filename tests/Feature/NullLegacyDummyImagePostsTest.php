<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NullLegacyDummyImagePostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_nulls_dummy_image_posts_but_keeps_placeholder(): void
    {
        $dummy = Post::create([
            'title' => 'Dummy',
            'subtitle' => 'Sub',
            'body' => '<p>Body</p>',
            'image' => 'images/DummyImage.png',
        ]);

        $placeholder = Post::create([
            'title' => 'Placeholder',
            'subtitle' => 'Sub',
            'body' => '<p>Body</p>',
            'image' => 'images/placeholder.png',
        ]);

        $real = Post::create([
            'title' => 'Real',
            'subtitle' => 'Sub',
            'body' => '<p>Body</p>',
            'image' => 'images/posts/foo.jpg',
        ]);

        $migration = require base_path('database/migrations/2026_09_06_000002_null_legacy_dummy_image_posts.php');
        $migration->up();

        $this->assertNull($dummy->fresh()->image);
        $this->assertSame('images/placeholder.png', $placeholder->fresh()->image);
        $this->assertSame('images/posts/foo.jpg', $real->fresh()->image);
    }
}