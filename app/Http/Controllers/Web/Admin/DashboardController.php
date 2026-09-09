<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use App\Services\Excel\ExcelExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function cleardata()
    {
        DashboardDataset::query()->delete();
        DashboardDatasetItem::query()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua data dashboard berhasil dihapus.',
        ]);
    }

    public function extract(Request $request)
    {
        $validatedData = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        if (! $validatedData) {
            return response()->json(['message' => 'Invalid file format. Please upload an Excel file.'], 400);
        }

        $excelExtractor = new ExcelExtractor;
        $datasets = $excelExtractor->extract($request->file('excel_file'));

        return response()->json([
            'success' => true,
            'message' => 'Data extracted successfully',
            'datasets' => $datasets,
        ]);
    }

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'datasets' => 'required|array|min:1',
            'datasets.*.title' => 'required|string|max:255',
            'datasets.*.chart_type' => 'required|in:bar,line,pie',
            'datasets.*.sheet_name' => 'nullable|string|max:255',
            'datasets.*.x_label' => 'nullable|string|max:255',
            'datasets.*.y_label' => 'nullable|string|max:255',
            'datasets.*.items' => 'required|array|min:1',
            'datasets.*.items.*.label' => 'required|string|max:255',
            'datasets.*.items.*.value' => 'required|numeric',
        ]);

        $results = DB::transaction(function () use ($validatedData) {
            DashboardDatasetItem::query()->delete();
            DashboardDataset::query()->delete();

            $usedSlugs = [];
            $datasetCount = 0;
            $itemCount = 0;

            foreach ($validatedData['datasets'] as $dataset) {
                $created = DashboardDataset::create([
                    'title' => $dataset['title'],
                    'slug' => $this->uniqueSlug($dataset['title'], $usedSlugs),
                    'sheet_name' => $dataset['sheet_name'] ?? $dataset['title'],
                    'chart_type' => $dataset['chart_type'],
                    'x_label' => $dataset['x_label'] ?? '',
                    'y_label' => $dataset['y_label'] ?? '',
                    'description' => null,
                ]);
                $datasetCount++;

                foreach (array_values($dataset['items']) as $index => $item) {
                    DashboardDatasetItem::create([
                        'dataset_id' => $created->id,
                        'label' => $item['label'],
                        'value' => $item['value'],
                        'sort_order' => $index + 1,
                    ]);
                    $itemCount++;
                }
            }

            return [
                'datasets_created' => $datasetCount,
                'items_created' => $itemCount,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil disimpan.',
            'results' => $results,
        ]);
    }

    public function create()
    {
        return view('AdminDashboard.create');
    }

    public function edit($id)
    {
        $dataset = DashboardDataset::with('items')->findOrFail($id);

        return view('AdminDashboard.edit', ['dataset' => $dataset]);
    }

    public function upload()
    {
        return view('AdminDashboard.upload');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'chart_type' => 'required|in:bar,line,pie',
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:255',
            'items.*.value' => 'required|numeric',
        ]);

        DB::transaction(function () use ($validatedData) {
            $usedSlugs = [];

            $created = DashboardDataset::create([
                'title' => $validatedData['title'],
                'slug' => $this->uniqueSlug($validatedData['title'], $usedSlugs),
                'sheet_name' => $validatedData['title'],
                'chart_type' => $validatedData['chart_type'],
                'x_label' => $validatedData['x_label'] ?? '',
                'y_label' => $validatedData['y_label'] ?? '',
                'description' => null,
            ]);

            foreach (array_values($validatedData['items']) as $index => $item) {
                DashboardDatasetItem::create([
                    'dataset_id' => $created->id,
                    'label' => $item['label'],
                    'value' => $item['value'],
                    'sort_order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('admin.dashboard')->with('success', 'Chart berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $dataset = DashboardDataset::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'chart_type' => 'required|in:bar,line,pie',
            'items' => 'required|array|min:1',
            'items.*.label' => 'required|string|max:255',
            'items.*.value' => 'required|numeric',
        ]);

        DB::transaction(function () use ($dataset, $validatedData) {
            $dataset->update([
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title']) . '-' . $dataset->id,
                'sheet_name' => $validatedData['title'],
                'chart_type' => $validatedData['chart_type'],
                'x_label' => $validatedData['x_label'] ?? $dataset->x_label,
                'y_label' => $validatedData['y_label'] ?? $dataset->y_label,
                'description' => $dataset->description,
            ]);

            $dataset->items()->delete();

            foreach (array_values($validatedData['items']) as $index => $item) {
                DashboardDatasetItem::create([
                    'dataset_id' => $dataset->id,
                    'label' => $item['label'],
                    'value' => $item['value'],
                    'sort_order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('admin.dashboard')->with('success', 'Chart berhasil diperbarui.');
    }

    private function uniqueSlug(string $title, array &$usedSlugs): string
    {
        $base = Str::slug($title) ?: 'chart';
        $slug = $base;
        $suffix = 2;

        while (in_array($slug, $usedSlugs, true) || DashboardDataset::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        $usedSlugs[] = $slug;

        return $slug;
    }
}
