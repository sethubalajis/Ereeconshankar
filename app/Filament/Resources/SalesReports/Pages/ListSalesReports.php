<?php

namespace App\Filament\Resources\SalesReports\Pages;

use App\Filament\Resources\SalesReports\SalesReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalesReports extends ListRecords
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\SalesReport::with('branch')->get();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'Sales Reports Summary',
                        $records,
                        [
                            'branch.name' => 'Branch',
                            'report_date' => 'Report Date',
                            'total_sales' => 'Total Sales',
                        ]
                    );
                }),
        ];
    }
}
