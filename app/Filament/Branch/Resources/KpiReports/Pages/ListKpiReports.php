<?php

namespace App\Filament\Branch\Resources\KpiReports\Pages;

use App\Filament\Branch\Resources\KpiReports\KpiReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKpiReports extends ListRecords
{
    protected static string $resource = KpiReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\KpiReport::all();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'KPI Reports Summary',
                        $records,
                        [
                            'kpi_name' => 'KPI Name',
                            'report_date' => 'Report Date',
                            'value' => 'Value',
                        ]
                    );
                }),
        ];
    }
}
