<?php

namespace Tests\Feature;

use App\Models\Post;
use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_writes_a_mix_of_null_and_placeholder_images(): void
    {
        $this->seed(PostSeeder::class);

        $nullImages = Post::whereNull('image')->count();
        $placeholderImages = Post::where('image', 'images/placeholder.png')->count();

        $this->assertGreaterThan(0, $nullImages);
        $this->assertGreaterThan(0, $placeholderImages);
    }
}