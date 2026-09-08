<?php

namespace Tests\Feature;

use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_returns_datasets_with_items_sorted_by_sort_order(): void
    {
        $first = DashboardDataset::create([
            'title' => 'Jumlah Mahasiswa',
            'slug' => 'jumlah-mahasiswa',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Mahasiswa',
            'description' => null,
        ]);
        DashboardDatasetItem::create(['dataset_id' => $first->id, 'label' => '2024', 'value' => 120, 'sort_order' => 2]);
        DashboardDatasetItem::create(['dataset_id' => $first->id, 'label' => '2023', 'value' => 100, 'sort_order' => 1]);

        $second = DashboardDataset::create([
            'title' => 'Gender',
            'slug' => 'gender',
            'sheet_name' => 'Sheet2',
            'chart_type' => 'pie',
            'x_label' => 'Gender',
            'y_label' => 'Jumlah',
            'description' => null,
        ]);
        DashboardDatasetItem::create(['dataset_id' => $second->id, 'label' => 'L', 'value' => 60, 'sort_order' => 1]);

        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'title', 'chart_type', 'x_label', 'y_label', 'labels', 'values'],
            ],
        ]);
        $response->assertJsonPath('data.0.title', 'Jumlah Mahasiswa');
        $response->assertJsonPath('data.0.labels', ['2023', '2024']);
        $response->assertJsonPath('data.0.values', [100, 120]);
        $response->assertJsonPath('data.1.title', 'Gender');
    }
}
