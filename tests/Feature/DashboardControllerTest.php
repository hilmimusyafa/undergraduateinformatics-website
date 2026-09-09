<?php

namespace Tests\Feature;

use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::create([
            'email' => fake()->unique()->safeEmail(),
            'password_recovery_id' => 1,
            'password' => bcrypt('password'),
        ]);
    }

    public function test_dashboard_page_requires_authentication(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('admin.login'));
    }

    public function test_dashboard_page_renders_saved_datasets(): void
    {
        $dataset = DashboardDataset::create([
            'title' => 'Jumlah Mahasiswa',
            'slug' => 'jumlah-mahasiswa',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Mahasiswa',
            'description' => null,
        ]);
        DashboardDatasetItem::create(['dataset_id' => $dataset->id, 'label' => '2024', 'value' => 120, 'sort_order' => 2]);
        DashboardDatasetItem::create(['dataset_id' => $dataset->id, 'label' => '2023', 'value' => 100, 'sort_order' => 1]);

        $response = $this->actingAs($this->createAdminUser())->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Jumlah Mahasiswa');
        $response->assertSee('/admin/dashboard/' . $dataset->id . '/edit');
    }

    public function test_save_replaces_all_datasets_with_edited_data(): void
    {
        $old = DashboardDataset::create([
            'title' => 'Dataset Lama',
            'slug' => 'dataset-lama',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Jumlah',
            'description' => null,
        ]);
        DashboardDatasetItem::create(['dataset_id' => $old->id, 'label' => '2023', 'value' => 10, 'sort_order' => 1]);

        $response = $this->actingAs($this->createAdminUser())->post('/admin/dashboard/save', [
            'datasets' => [
                [
                    'title' => 'Jumlah Mahasiswa',
                    'chart_type' => 'line',
                    'x_label' => 'Tahun',
                    'y_label' => 'Mahasiswa',
                    'items' => [
                        ['label' => '2023', 'value' => 100],
                        ['label' => '2024', 'value' => 120],
                    ],
                ],
                [
                    'title' => 'Gender',
                    'chart_type' => 'pie',
                    'x_label' => 'Gender',
                    'y_label' => 'Jumlah',
                    'items' => [
                        ['label' => 'L', 'value' => 60],
                    ],
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertSame(2, DashboardDataset::count());
        $this->assertSame(3, DashboardDatasetItem::count());

        $saved = DashboardDataset::with('items')->first();
        $this->assertSame('Jumlah Mahasiswa', $saved->title);
        $this->assertSame('line', $saved->chart_type);
        $this->assertSame(['2023', '2024'], $saved->items->pluck('label')->values()->all());
        $this->assertSame([100, 120], $saved->items->pluck('value')->map(fn ($value) => (int) $value)->values()->all());
        $this->assertSame([1, 2], $saved->items->pluck('sort_order')->values()->all());
    }

    public function test_save_keeps_duplicate_titles_unique(): void
    {
        $this->actingAs($this->createAdminUser())->post('/admin/dashboard/save', [
            'datasets' => [
                [
                    'title' => 'Sama',
                    'chart_type' => 'bar',
                    'x_label' => 'Tahun',
                    'items' => [['label' => '2023', 'value' => 1]],
                ],
                [
                    'title' => 'Sama',
                    'chart_type' => 'line',
                    'x_label' => 'Tahun',
                    'items' => [['label' => '2024', 'value' => 2]],
                ],
            ],
        ])->assertJsonPath('success', true);

        $slugs = DashboardDataset::query()->pluck('slug')->values()->all();
        $this->assertCount(2, array_unique($slugs));
    }

    public function test_update_edits_single_dataset_values_and_type(): void
    {
        $dataset = DashboardDataset::create([
            'title' => 'Jumlah Mahasiswa',
            'slug' => 'jumlah-mahasiswa',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Mahasiswa',
            'description' => null,
        ]);
        DashboardDatasetItem::create(['dataset_id' => $dataset->id, 'label' => '2023', 'value' => 100, 'sort_order' => 1]);

        $response = $this->actingAs($this->createAdminUser())->post("/admin/dashboard/{$dataset->id}/update", [
            'title' => 'Mahasiswa per Tahun',
            'chart_type' => 'pie',
            'x_label' => 'Tahun',
            'y_label' => 'Mahasiswa',
            'items' => [
                ['label' => '2023', 'value' => 100],
                ['label' => '2024', 'value' => 130],
                ['label' => '2025', 'value' => 150],
            ],
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertSame(1, DashboardDataset::count());
        $this->assertSame(3, DashboardDatasetItem::count());

        $saved = $dataset->fresh()->load('items');
        $this->assertSame('Mahasiswa per Tahun', $saved->title);
        $this->assertSame('pie', $saved->chart_type);
        $this->assertSame(['2023', '2024', '2025'], $saved->items->pluck('label')->values()->all());
        $this->assertSame([1, 2, 3], $saved->items->pluck('sort_order')->values()->all());
    }

    public function test_store_appends_new_dataset_without_deleting_existing(): void
    {
        $existing = DashboardDataset::create([
            'title' => 'Dataset Lama',
            'slug' => 'dataset-lama',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Jumlah',
            'description' => null,
        ]);
        DashboardDatasetItem::create(['dataset_id' => $existing->id, 'label' => '2023', 'value' => 10, 'sort_order' => 1]);

        $response = $this->actingAs($this->createAdminUser())->post('/admin/dashboard/store', [
            'title' => 'Chart Manual',
            'chart_type' => 'pie',
            'x_label' => 'Gender',
            'y_label' => 'Jumlah',
            'items' => [
                ['label' => 'L', 'value' => 60],
                ['label' => 'P', 'value' => 40],
            ],
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertSame(2, DashboardDataset::count());
        $this->assertSame(3, DashboardDatasetItem::count());

        $created = DashboardDataset::where('title', 'Chart Manual')->first();
        $this->assertNotNull($created);
        $this->assertSame('pie', $created->chart_type);
        $this->assertSame(['L', 'P'], $created->items->pluck('label')->values()->all());
        $this->assertSame([1, 2], $created->items->pluck('sort_order')->values()->all());
        $this->assertSame('Dataset Lama', $existing->fresh()->title);
    }

    public function test_store_makes_slug_unique_against_existing(): void
    {
        DashboardDataset::create([
            'title' => 'Jumlah',
            'slug' => 'jumlah',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Jumlah',
            'description' => null,
        ]);

        $this->actingAs($this->createAdminUser())
            ->post('/admin/dashboard/store', [
                'title' => 'Jumlah',
                'chart_type' => 'line',
                'items' => [['label' => '2024', 'value' => 5]],
            ])->assertRedirect(route('admin.dashboard'));

        $this->assertSame('jumlah-2', DashboardDataset::latest('id')->first()->slug);
    }

    public function test_update_requires_valid_chart_type(): void
    {
        $dataset = DashboardDataset::create([
            'title' => 'Jumlah Mahasiswa',
            'slug' => 'jumlah-mahasiswa',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Mahasiswa',
            'description' => null,
        ]);

        $this->actingAs($this->createAdminUser())
            ->post("/admin/dashboard/{$dataset->id}/update", [
                'title' => 'Invalid',
                'chart_type' => 'radar',
                'items' => [['label' => '2023', 'value' => 1]],
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('chart_type');
    }

    public function test_cleardata_removes_all_datasets(): void
    {
        $dataset = DashboardDataset::create([
            'title' => 'Jumlah Mahasiswa',
            'slug' => 'jumlah-mahasiswa',
            'sheet_name' => 'Sheet1',
            'chart_type' => 'bar',
            'x_label' => 'Tahun',
            'y_label' => 'Mahasiswa',
            'description' => null,
        ]);
        DashboardDatasetItem::create(['dataset_id' => $dataset->id, 'label' => '2023', 'value' => 100, 'sort_order' => 1]);

        $this->actingAs($this->createAdminUser())
            ->delete('/admin/dashboard/cleardata')
            ->assertJsonPath('success', true);

        $this->assertSame(0, DashboardDataset::count());
        $this->assertSame(0, DashboardDatasetItem::count());
    }
}