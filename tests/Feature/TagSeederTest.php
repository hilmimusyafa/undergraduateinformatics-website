<?php

namespace Tests\Feature;

use App\Models\Tag;
use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_seeder_seeds_tags_posts_and_relations(): void
    {
        $this->seed(TagSeeder::class);

        $this->assertDatabaseCount('tags', 7);
        $this->assertDatabaseCount('posts', 6);
        $this->assertDatabaseCount('post_tags', 12);
    }

    public function test_tag_seeder_is_idempotent(): void
    {
        $this->seed(TagSeeder::class);
        $this->seed(TagSeeder::class);

        $this->assertDatabaseCount('tags', 7);
        $this->assertDatabaseCount('posts', 6);
        $this->assertDatabaseCount('post_tags', 12);
    }

    public function test_tag_without_posts_has_zero_count(): void
    {
        $this->seed(TagSeeder::class);

        $exchange = Tag::where('name', 'Pertukaran Mahasiswa')->withCount('posts')->first();

        $this->assertNotNull($exchange);
        $this->assertSame(0, $exchange->posts_count);
    }

    public function test_default_tag_has_all_posts_attached(): void
    {
        $this->seed(TagSeeder::class);

        $defaultTag = Tag::where('name', 'S1 Informatika')->withCount('posts')->first();

        $this->assertNotNull($defaultTag);
        $this->assertSame(6, $defaultTag->posts_count);
    }

    public function test_database_seeder_calls_tag_seeder(): void
    {
        $this->seed();

        $this->assertDatabaseHas('tags', ['name' => 'S1 Informatika']);
        $this->assertSame(7, Tag::count());
    }

    public function test_each_tag_has_expected_post_count(): void
    {
        $this->seed(TagSeeder::class);

        $expected = [
            'S1 Informatika' => 6,
            'Beasiswa' => 1,
            'Akademik' => 2,
            'MBKM' => 1,
            'Kemahasiswaan' => 1,
            'Tugas Akhir' => 1,
            'Pertukaran Mahasiswa' => 0,
        ];

        foreach ($expected as $name => $count) {
            $tag = Tag::where('name', $name)->withCount('posts')->first();
            $this->assertNotNull($tag);
            $this->assertSame($count, $tag->posts_count);
        }
    }
}
