<?php

namespace Tests\Feature\Web;

use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_statistics_page(): void
    {
        $response = $this->get('/statistik-mahasiswa');

        $response->assertStatus(200);
        $response->assertViewIs('StatisticsPage');
        $response->assertSee('Statistik Mahasiswa');
    }

    public function test_legacy_or_short_statistik_url_redirects_to_statistics_page(): void
    {
        $response = $this->get('/statistik');

        $response->assertRedirect('/statistik-mahasiswa');
    }

    public function test_statistics_page_displays_datasets_and_items_in_data_table(): void
    {
        $dataset = DashboardDataset::create([
            'title' => 'Statistik Mahasiswa Aktif',
            'slug' => 'statistik-mahasiswa-aktif',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Angkatan',
            'y_label' => 'Jumlah Mahasiswa',
            'description' => 'Data mahasiswa aktif per angkatan.',
        ]);

        DashboardDatasetItem::create([
            'dataset_id' => $dataset->id,
            'label' => 'Angkatan 2021',
            'value' => 350,
            'sort_order' => 1,
        ]);

        DashboardDatasetItem::create([
            'dataset_id' => $dataset->id,
            'label' => 'Angkatan 2022',
            'value' => 410,
            'sort_order' => 2,
        ]);

        $response = $this->get('/statistik-mahasiswa');

        $response->assertStatus(200);
        $response->assertSee('Statistik Mahasiswa Aktif');
        $response->assertSee('Angkatan 2021');
        $response->assertSee('350');
        $response->assertSee('Angkatan 2022');
        $response->assertSee('410');
        $response->assertSee('Data mahasiswa aktif per angkatan.');
    }

    public function test_statistics_page_shows_empty_state_when_no_datasets(): void
    {
        $response = $this->get('/statistik-mahasiswa');

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Data Statistik');
    }
}
