<?php

namespace Tests\Feature\Web;

use App\Models\ImportantLink;
use App\Models\ImportantSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_links_returns_sections_grouped_with_nested_links(): void
    {
        $first = ImportantSection::create(['name' => 'Kumpulan Link MBKM', 'order_number' => 1]);
        $second = ImportantSection::create(['name' => 'Kumpulan Link Kelas', 'order_number' => 2]);

        ImportantLink::create(['important_section_id' => $first->id, 'name' => 'Angkatan 2020', 'link' => 'http://bit.ly/MBKM2020']);
        ImportantLink::create(['important_section_id' => $second->id, 'name' => 'Angkatan 2019', 'link' => 'http://bit.ly/Kelas2019']);

        $response = $this->getJson('/api/links');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'name', 'order_number', 'links' => ['*' => ['id', 'name', 'link', 'updated_at']]],
            ],
        ]);
        $response->assertJsonPath('data.0.name', 'Kumpulan Link MBKM');
        $response->assertJsonPath('data.0.links.0.name', 'Angkatan 2020');
        $response->assertJsonPath('data.1.name', 'Kumpulan Link Kelas');
        $response->assertJsonPath('data.1.links.0.name', 'Angkatan 2019');
    }

    public function test_api_links_orders_sections_by_order_number(): void
    {
        ImportantSection::create(['name' => 'Section B', 'order_number' => 2]);
        ImportantSection::create(['name' => 'Section A', 'order_number' => 1]);

        $response = $this->getJson('/api/links');

        $response->assertJsonPath('data.0.name', 'Section A');
        $response->assertJsonPath('data.1.name', 'Section B');
    }

    public function test_api_links_orders_links_newest_first_within_section(): void
    {
        $section = ImportantSection::create(['name' => 'Kumpulan Link MBKM', 'order_number' => 1]);

        $older = ImportantLink::create(['important_section_id' => $section->id, 'name' => 'Angkatan 2019', 'link' => 'http://bit.ly/MBKM2019']);
        $older->update(['created_at' => now()->subDays(10), 'updated_at' => now()->subDays(10)]);

        $newer = ImportantLink::create(['important_section_id' => $section->id, 'name' => 'Angkatan 2020', 'link' => 'http://bit.ly/MBKM2020']);
        $newer->update(['created_at' => now()->subDay(), 'updated_at' => now()->subDay()]);

        $response = $this->getJson('/api/links');

        $response->assertJsonPath('data.0.links.0.name', 'Angkatan 2020');
        $response->assertJsonPath('data.0.links.1.name', 'Angkatan 2019');
    }

    public function test_api_links_breaks_link_updated_at_ties_by_id_desc(): void
    {
        $section = ImportantSection::create(['name' => 'Kumpulan Link MBKM', 'order_number' => 1]);

        $first = ImportantLink::create(['important_section_id' => $section->id, 'name' => 'Angkatan 2019', 'link' => 'http://bit.ly/MBKM2019']);
        $first->update(['created_at' => now()->subDay(), 'updated_at' => now()->subDay()]);

        $second = ImportantLink::create(['important_section_id' => $section->id, 'name' => 'Angkatan 2020', 'link' => 'http://bit.ly/MBKM2020']);
        $second->update(['created_at' => now()->subDay(), 'updated_at' => now()->subDay()]);

        $response = $this->getJson('/api/links');

        $response->assertJsonPath('data.0.links.0.name', 'Angkatan 2020');
        $response->assertJsonPath('data.0.links.1.name', 'Angkatan 2019');
    }

    public function test_api_links_includes_section_without_links(): void
    {
        ImportantSection::create(['name' => 'Kumpulan Link Tugas Akhir', 'order_number' => 1]);

        $response = $this->getJson('/api/links');

        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.links', []);
    }

    public function test_api_links_returns_empty_data_when_no_sections(): void
    {
        $response = $this->getJson('/api/links');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('data', []);
    }

    public function test_web_links_route_renders_app_wrapper_with_initial_data_and_seo_tags(): void
    {
        $section = ImportantSection::create(['name' => 'Kumpulan Link MBKM', 'order_number' => 1]);
        ImportantLink::create(['important_section_id' => $section->id, 'name' => 'Angkatan 2020', 'link' => 'http://bit.ly/MBKM2020']);

        $response = $this->get('/links');

        $response->assertStatus(200);
        $response->assertViewIs('app');
        $response->assertViewHas('initialData');
        $response->assertSee('__INITIAL_DATA__', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('CollectionPage', false);

        preg_match('/window\.__INITIAL_DATA__ = (\{.*?\});/s', $response->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Initial data script tag not found');
        $initialData = json_decode($matches[1], true);
        $this->assertSame('success', $initialData['status']);
        $this->assertSame('Kumpulan Link MBKM', $initialData['data'][0]['name']);
        $this->assertSame('Angkatan 2020', $initialData['data'][0]['links'][0]['name']);
    }
}
