<?php

namespace App\Filament\Branch\Resources\GstReports\Pages;

use App\Filament\Branch\Resources\GstReports\GstReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGstReports extends ListRecords
{
    protected static string $resource = GstReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\GstReport::all();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'GST Reports Summary',
                        $records,
                        [
                            'report_type' => 'Report Type',
                            'period_start' => 'Period Start',
                            'period_end' => 'Period End',
                        ]
                    );
                }),
        ];
    }
}
