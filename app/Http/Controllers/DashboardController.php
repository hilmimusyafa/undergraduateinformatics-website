<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardDatasetResource;
use App\Models\DashboardDataset;
use App\Models\DashboardDatasetItem;
use App\Services\Excel\ExcelExtractor;
use App\Services\Excel\PushtoDatabase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function fetch()
    {
        $datasets = DashboardDatasetResource::collection(
            DashboardDataset::with('items')->orderBy('id')->get()
        )->resolve();

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

        if (! $validatedData) {
            return response()->json(['message' => 'Invalid file format. Please upload an Excel file.'], 400);
        }

        $excelExtractor = new ExcelExtractor;
        $datasets = $excelExtractor->extract($excelFile);

        return response()->json([
            'success' => true,
            'message' => 'Data extracted successfully',
            'datasets' => $datasets,
        ]);
    }

    public function pushdata(Request $request)
    {
        $excelFile = $request->file('excel_file');

        $validatedData = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        if (! $validatedData) {
            return response()->json(['message' => 'Invalid file format. Please upload an Excel file.'], 400);
        }

        $excelExtractor = new ExcelExtractor;
        $datasets = $excelExtractor->extract($excelFile);

        $pushToDatabase = new PushtoDatabase;
        $pushResults = $pushToDatabase->pushToDatabase($datasets);

        return response()->json([
            'success' => true,
            'message' => 'Data pushed successfully',
            'results' => $pushResults,
        ]);
    }
}
