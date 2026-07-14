<?php

namespace App\Services\Excel;

use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PushtoDatabase
{
    public function pushToDatabase(array $datasets): array
    {
        return DB::transaction(function () use ($datasets) {

        DashboardDatasetItem::query()->delete();
        DashboardDataset::query()->delete();

        $datasetCount = 0;
        $itemCount = 0;

        foreach ($datasets as $dataset) {

            $dashboardDataset = DashboardDataset::create([
                'title'       => $dataset['title'],
                'slug'        => Str::slug($dataset['title']),
                'sheet_name'  => $dataset['sheet_name'],
                'chart_type'  => $this->detectChartType($dataset['x_label']),
                'x_label'     => $dataset['x_label'],
                'y_label'     => $dataset['y_label'],
                'description' => null,
            ]);

            $datasetCount++;

            foreach ($dataset['items'] as $index => $item) {

                DashboardDatasetItem::create([
                    'dataset_id' => $dashboardDataset->id,
                    'label'      => $item['label'],
                    'value'      => $item['value'],
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
}

public function detectChartType(string $header): string
{
    $header = strtolower($header);

    if (str_contains($header, 'tahun') || str_contains($header, 'angkatan')) {
        return 'bar';
    }

    if (str_contains($header, 'bulan') || str_contains($header, 'tanggal')) {
        return 'line';
    }

    if (
        str_contains($header, 'daerah') ||
        str_contains($header, 'provinsi') ||
        str_contains($header, 'agama') ||
        str_contains($header, 'gender')
    ) {
        return 'pie';
    }

    return 'bar';
}
}   