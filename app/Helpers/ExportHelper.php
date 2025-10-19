<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Collection;

class ExportHelper
{
    public static function exportToPdf(string $viewPath, array $data, string $filename)
    {
        $pdf = Pdf::loadView($viewPath, $data);
        return $pdf->download($filename);
    }

    

public static function exportToCsv(iterable $data, array $columns, \Closure $mapFn, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($data, $columns, $mapFn) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            foreach ($data as $row) {
                fputcsv($handle, $mapFn($row));
            }
            fclose($handle);
        }, 200, $headers);
    }
}
