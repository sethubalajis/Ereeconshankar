<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

trait ExportsReportData
{
    /**
     * Get the base query for the report data.
     * This checks for standard Filament queries or custom defined queries.
     */
    protected function getExportQuery()
    {
        $query = null;

        if (method_exists($this, 'getFilteredTableQuery')) {
            $query = $this->getFilteredTableQuery();
        } elseif (method_exists($this, 'getTableQuery')) {
            $query = $this->getTableQuery();
        }

        if (!$query && method_exists($this, 'getFinancialDataQuery')) {
            $query = $this->getFinancialDataQuery();
        }

        if (!$query) {
            if (method_exists($this, 'getHrDataQuery')) $query = $this->getHrDataQuery();
            if (method_exists($this, 'getKpiDataQuery')) $query = $this->getKpiDataQuery();
        }

        return $query;
    }

    /**
     * Format an attribute value for export (e.g. handle nulls, decimals, etc.)
     */
    protected function formatExportValue($value)
    {
        if (is_numeric($value)) {
            // Check if it's a floating point number
            if (floor((float)$value) != (float)$value) {
                return number_format((float)$value, 2, '.', '');
            }
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        return $value ?? '';
    }

    /**
     * Export the current report query to a generic CSV file.
     */
    public function exportToCsv()
    {
        $query = $this->getExportQuery();
        if (!$query) {
            return null;
        }

        $records = $query->get();
        if ($records->isEmpty()) {
            return null;
        }

        $baseName = str_replace('DataReportTable', '', class_basename(static::class));
        $fileName = $baseName . '_Report_' . date('Ymd_His') . '.csv';

        return Response::streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fputs($out, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));

            $first = true;
            foreach ($records as $record) {
                $attributes = is_array($record) ? $record : $record->toArray();
                
                // Hide common unwanted internal ID columns if they exist and are not meant for display
                unset($attributes['id'], $attributes['branch_id'], $attributes['created_at'], $attributes['updated_at'], $attributes['deleted_at']);
                
                if ($first) {
                    $headers = array_map(function($key) {
                        return Str::title(str_replace('_', ' ', $key));
                    }, array_keys($attributes));
                    
                    fputcsv($out, $headers);
                    $first = false;
                }

                $formattedValues = array_map(fn($val) => $this->formatExportValue($val), array_values($attributes));
                fputcsv($out, $formattedValues);
            }
            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export the current report query to a generic PDF file.
     */
    public function exportToPdf()
    {
        $query = $this->getExportQuery();
        if (!$query) {
            return null;
        }

        $records = $query->get();
        if ($records->isEmpty()) {
            return null;
        }

        $baseName = str_replace('DataReportTable', '', class_basename(static::class));
        $title = preg_replace('/(?<!^)([A-Z])/', ' $1', $baseName) . ' Report';
        $fileName = $baseName . '_Report_' . date('Ymd_His') . '.pdf';

        // Prepare data for the generic view
        $formattedRecords = [];
        $headers = [];
        $first = true;
        
        foreach ($records as $record) {
            $attributes = is_array($record) ? $record : $record->toArray();
            unset($attributes['id'], $attributes['branch_id'], $attributes['created_at'], $attributes['updated_at'], $attributes['deleted_at']);
            
            if ($first) {
                $headers = array_map(function($key) {
                    return Str::title(str_replace('_', ' ', $key));
                }, array_keys($attributes));
                $first = false;
            }
            
            $formattedValues = array_map(fn($val) => $this->formatExportValue($val), array_values($attributes));
            $formattedRecords[] = $formattedValues;
        }

        $data = [
            'title' => $title,
            'fromDate' => $this->fromDate ?? null,
            'toDate' => $this->toDate ?? null,
            'reportType' => $this->reportType ?? null,
            'headers' => $headers,
            'records' => $formattedRecords,
        ];

        $pdf = Pdf::loadView('pdf.generic-report', $data)->setPaper('a4', 'landscape');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }
}
