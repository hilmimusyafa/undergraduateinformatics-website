<?php

namespace Tests\Feature;

use App\Models\Tag;
use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_seeder_seeds_seven_tags(): void
    {
        $this->seed(TagSeeder::class);

        $this->assertDatabaseCount('tags', 7);
        $this->assertDatabaseCount('posts', 0);
        $this->assertDatabaseCount('post_tags', 0);
    }

    public function test_tag_seeder_is_idempotent(): void
    {
        $this->seed(TagSeeder::class);
        $this->seed(TagSeeder::class);

        $this->assertDatabaseCount('tags', 7);
    }

    public function test_tag_without_posts_has_zero_count(): void
    {
        $this->seed();

        $mbkm = Tag::where('name', 'MBKM')->withCount('posts')->first();

        $this->assertNotNull($mbkm);
        $this->assertSame(0, $mbkm->posts_count);
    }

    public function test_default_tag_has_all_posts_attached(): void
    {
        $this->seed();

        $defaultTag = Tag::where('name', 'S1 Informatika')->withCount('posts')->first();

        $this->assertNotNull($defaultTag);
        $this->assertSame(30, $defaultTag->posts_count);
    }

    public function test_each_tag_has_expected_post_count(): void
    {
        $this->seed();

        $expected = [
            'S1 Informatika' => 30,
            'Tugas Akhir' => 7,
            'Registrasi Semester' => 17,
            'IuM' => 4,
            'MBKM' => 0,
            'Sidang' => 3,
            'Perkuliahan' => 4,
        ];

        foreach ($expected as $name => $count) {
            $tag = Tag::where('name', $name)->withCount('posts')->first();
            $this->assertNotNull($tag);
            $this->assertSame($count, $tag->posts_count);
        }
    }
}
