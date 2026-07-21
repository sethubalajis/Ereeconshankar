<?php

namespace App\Filament\Branch\Resources\PurchaseReports\Pages;

use App\Filament\Branch\Resources\PurchaseReports\PurchaseReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseReports extends ListRecords
{
    protected static string $resource = PurchaseReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
