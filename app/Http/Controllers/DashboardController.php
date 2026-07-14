<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use App\Services\Excel\ExcelExtractor;
use App\Services\Excel\PushtoDatabase;

class DashboardController extends Controller
{
    public function fetch()
    {
        $datasets = DashboardDataset::with('items')
            ->orderBy('id')
            ->get()
            ->map(function ($dataset) {

                return [
                    'id' => $dataset->id,
                    'title' => $dataset->title,
                    'chart_type' => $dataset->chart_type,
                    'x_label' => $dataset->x_label,
                    'y_label' => $dataset->y_label,

                    'labels' => $dataset->items
                        ->sortBy('sort_order')
                        ->pluck('label')
                        ->values(),

                    'values' => $dataset->items
                        ->sortBy('sort_order')
                        ->pluck('value')
                        ->values(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $datasets,
        ]);
    }  
    
    public function cleardata()
    {
        DashboardDataset::query()->delete();
        DashboardDatasetItem::query()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All dashboard data cleared successfully.',
        ]);
    }

    public function extract(Request $request)
    {
        $excelFile = $request->file('excel_file');

        $validatedData = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        if (!$validatedData) {
            return response()->json(['message' => 'Invalid file format. Please upload an Excel file.'], 400);
        }

        $excelExtractor = new ExcelExtractor();
        $datasets = $excelExtractor->extract($excelFile);
        
        return response()->json([
            'success' => true,
            'message' => 'Data extracted successfully',
            'datasets' => $datasets
        ]);
    }

    public function pushdata(Request $request)
    {
        $excelFile = $request->file('excel_file');

        $validatedData = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        if (!$validatedData) {
            return response()->json(['message' => 'Invalid file format. Please upload an Excel file.'], 400);
        }

        $excelExtractor = new ExcelExtractor();
        $datasets = $excelExtractor->extract($excelFile);

        $pushToDatabase = new PushtoDatabase();
        $pushResults = $pushToDatabase->pushToDatabase($datasets);
        return response()->json([
            'success' => true,
            'message' => 'Data pushed successfully',
            'results' => $pushResults
        ]);
    }
}
