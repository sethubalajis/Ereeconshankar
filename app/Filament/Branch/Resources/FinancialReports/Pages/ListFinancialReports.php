<?php

namespace App\Filament\Branch\Resources\FinancialReports\Pages;

use App\Filament\Branch\Resources\FinancialReports\FinancialReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinancialReports extends ListRecords
{
    protected static string $resource = FinancialReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\FinancialReport::all();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'Financial Reports Summary',
                        $records,
                        [
                            'report_type' => 'Report Type',
                            'as_of_date' => 'As Of Date',
                        ]
                    );
                }),
        ];
    }
}
