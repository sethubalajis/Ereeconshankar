<?php

namespace App\Filament\Resources\StockTransactionReports\Pages;

use App\Filament\Resources\StockTransactionReports\StockTransactionReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockTransactionReports extends ListRecords
{
    protected static string $resource = StockTransactionReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\StockTransactionReport::with('branch')->get();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'Stock Transaction Reports Summary',
                        $records,
                        [
                            'branch.name' => 'Branch',
                            'report_date' => 'Report Date',
                            'total_inventory_value' => 'Total Inventory Value',
                        ]
                    );
                }),
        ];
    }
}
