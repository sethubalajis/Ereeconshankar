<?php

namespace App\Filament\Branch\Resources\InventoryReports\Pages;

use App\Filament\Branch\Resources\InventoryReports\InventoryReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryReports extends ListRecords
{
    protected static string $resource = InventoryReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $records = \App\Models\InventoryReport::with('branch')->get();
                    return \App\Helpers\ReportPdfHelper::downloadListPdf(
                        'Inventory Reports Summary',
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
