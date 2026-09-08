<?php

namespace Tests\Feature\Web;

use App\Models\ImportantLink;
use App\Models\ImportantSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

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