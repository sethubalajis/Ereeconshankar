<?php

namespace App\Filament\Branch\Resources\ServiceReports\Pages;

use App\Filament\Branch\Resources\ServiceReports\ServiceReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceReports extends ListRecords
{
    protected static string $resource = ServiceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\ServiceReport::with('serviceRequest')->get();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'Service Reports Summary',
                        $records,
                        [
                            'serviceRequest.request_number' => 'Service Request No.',
                            'report_date' => 'Report Date',
                        ]
                    );
                }),
        ];
    }
}
