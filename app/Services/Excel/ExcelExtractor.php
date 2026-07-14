<?php

namespace App\Services\Excel;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExtractor
{
    public function extract(UploadedFile|string $file): array
    {
        if ($file instanceof UploadedFile) {
            Validator::make(
                ['file' => $file],
                [
                    'file' => [
                        'required',
                        'mimes:xlsx,xls'
                    ]
                ]
            )->validate();
        }

        $workbook = Excel::toArray([], $file);

        $datasets = [];

        foreach ($workbook as $sheetIndex => $rows) {

            if (count($rows) <= 1) {
                continue;
            }

            $sheetName = "Sheet " . ($sheetIndex + 1);

            $header = $rows[0];

            $xLabel = trim((string) ($header[0] ?? "Label"));
            $yLabel = trim((string) ($header[1] ?? "Value"));

            $items = [];

            foreach (array_slice($rows, 1) as $row) {

                $label = trim((string) ($row[0] ?? ""));
                $value = $row[1] ?? null;

                if ($label === "" && $value === null) {
                    continue;
                }

                if (!is_numeric($value)) {
                    continue;
                }

                $items[] = [
                    "label" => $label,
                    "value" => (float) $value,
                ];
            }

            if (empty($items)) {
                continue;
            }

            $datasets[] = [
                "title" => $sheetName,
                "sheet_name" => $sheetName,
                "x_label" => $xLabel,
                "y_label" => $yLabel,
                "items" => $items,
            ];
        }

        return $datasets;
    }
}