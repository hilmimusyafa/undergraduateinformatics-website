<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_image_is_false_when_image_is_null(): void
    {
        $post = Post::create([
            'title' => 'Tanpa Gambar',
            'subtitle' => 'Sub',
            'body' => '<p>Body</p>',
            'image' => null,
        ]);

        $this->assertFalse($post->hasImage());
    }

    public function test_has_image_is_true_when_image_has_a_path(): void
    {
        $post = Post::create([
            'title' => 'Dengan Gambar',
            'subtitle' => 'Sub',
            'body' => '<p>Body</p>',
            'image' => 'images/posts/foo.jpg',
        ]);

        $this->assertTrue($post->hasImage());
    }

    public function test_has_image_is_false_for_placeholder_image(): void
    {
        $post = Post::create([
            'title' => 'Placeholder',
            'subtitle' => 'Sub',
            'body' => '<p>Body</p>',
            'image' => 'images/placeholder.png',
        ]);

        $this->assertFalse($post->hasImage());
    }
}