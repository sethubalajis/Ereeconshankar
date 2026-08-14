<?php

namespace App\Filament\Branch\Resources\HrReports\Pages;

use App\Filament\Branch\Resources\HrReports\HrReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHrReports extends ListRecords
{
    protected static string $resource = HrReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\HrReport::all();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'HR Reports Summary',
                        $records,
                        [
                            'report_month' => 'Report Month',
                            'total_employees' => 'Total Employees',
                            'new_hires' => 'New Hires',
                            'exits' => 'Exits',
                        ]
                    );
                }),
        ];
    }
}
