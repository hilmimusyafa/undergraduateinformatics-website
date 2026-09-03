<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_gets_slug_from_name_on_create(): void
    {
        $tag = Tag::create(['name' => 'Beasiswa']);

        $this->assertSame('beasiswa', $tag->slug);
    }

    public function test_duplicate_tag_names_get_unique_suffix(): void
    {
        $first = Tag::create(['name' => 'Beasiswa']);
        $second = Tag::create(['name' => 'Beasiswa']);

        $this->assertSame('beasiswa', $first->slug);
        $this->assertSame('beasiswa-2', $second->slug);
    }

    public function test_renaming_tag_keeps_slug(): void
    {
        $tag = Tag::create(['name' => 'Beasiswa']);

        $tag->update(['name' => 'Beasiswa Dalam Negeri']);

        $this->assertSame('beasiswa', $tag->fresh()->slug);
    }

    public function test_empty_slug_source_falls_back_to_tag(): void
    {
        $tag = Tag::create(['name' => '!!!']);

        $this->assertSame('tag', $tag->slug);
    }
}
