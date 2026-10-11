<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DashboardDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(Request $request): View
    {
        $statistics = collect();
        if (Schema::hasTable('dashboard_datasets') && Schema::hasTable('dashboard_dataset_items')) {
            $statistics = DashboardDataset::query()
                ->with(['items' => fn ($query) => $query->orderBy('sort_order')])
                ->orderBy('id')
                ->get();
        }

        $statisticsPayload = $statistics->map(function ($dataset) {
            return [
                'id' => $dataset->id,
                'chart_type' => $dataset->chart_type,
                'labels' => $dataset->items->pluck('label')->all(),
                'values' => $dataset->items->pluck('value')->all(),
            ];
        })->values()->all();

        return view('StatisticsPage', [
            'statistics' => $statistics,
            'statisticsPayload' => $statisticsPayload,
        ]);
    }
}
