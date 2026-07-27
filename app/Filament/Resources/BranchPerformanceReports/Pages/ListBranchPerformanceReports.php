<?php

namespace App\Filament\Resources\BranchPerformanceReports\Pages;

use App\Filament\Resources\BranchPerformanceReports\BranchPerformanceReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchPerformanceReports extends ListRecords
{
    protected static string $resource = BranchPerformanceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\BranchPerformanceReport::with('branch')->get();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'Branch Performance Reports Summary',
                        $records,
                        [
                            'branch.name' => 'Branch',
                            'report_month' => 'Report Month',
                            'score' => 'Performance Score',
                        ]
                    );
                }),
        ];
    }
}
