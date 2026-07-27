<?php

namespace App\Filament\Resources\PurchaseReports\Pages;

use App\Filament\Resources\PurchaseReports\PurchaseReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseReports extends ListRecords
{
    protected static string $resource = PurchaseReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\PurchaseReport::with('branch')->get();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'Purchase Reports Summary',
                        $records,
                        [
                            'branch.name' => 'Branch',
                            'report_date' => 'Report Date',
                            'total_purchases' => 'Total Purchases',
                        ]
                    );
                }),
        ];
    }
}
