<?php

namespace Tests\Feature;

use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use Database\Seeders\DashboardDatasetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardDatasetSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_dataset_seeder_seeds_datasets_and_items(): void
    {
        $this->seed(DashboardDatasetSeeder::class);

        $this->assertDatabaseCount('dashboard_datasets', 4);
        $this->assertDatabaseCount('dashboard_dataset_items', 17);
    }

    public function test_dashboard_dataset_seeder_is_idempotent(): void
    {
        $this->seed(DashboardDatasetSeeder::class);
        $this->seed(DashboardDatasetSeeder::class);

        $this->assertSame(4, DashboardDataset::count());
        $this->assertSame(17, DashboardDatasetItem::count());
    }

    public function test_dashboard_dataset_seeder_preserves_sort_order(): void
    {
        $this->seed(DashboardDatasetSeeder::class);

        $dataset = DashboardDataset::where('slug', 'jumlah-mahasiswa-per-angkatan')->first();

        $this->assertNotNull($dataset);
        $this->assertSame('bar', $dataset->chart_type);
        $this->assertSame(
            ['2022', '2023', '2024', '2025'],
            $dataset->items->sortBy('sort_order')->pluck('label')->values()->all()
        );
        $this->assertSame(
            [240.0, 255.0, 270.0, 285.0],
            $dataset->items->sortBy('sort_order')->pluck('value')->values()->all()
        );
    }

    public function test_dashboard_dataset_seeder_covers_all_chart_types(): void
    {
        $this->seed(DashboardDatasetSeeder::class);

        $this->assertSame(
            ['bar', 'line', 'pie'],
            DashboardDataset::query()->orderBy('id')->distinct()->pluck('chart_type')->sort()->values()->all()
        );
    }
}
