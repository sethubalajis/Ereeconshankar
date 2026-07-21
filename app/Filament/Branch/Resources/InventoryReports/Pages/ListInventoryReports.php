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
        ];
    }
}
