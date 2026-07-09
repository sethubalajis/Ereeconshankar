<?php

namespace App\Filament\Resources\InventoryReports\Pages;

use App\Filament\Resources\InventoryReports\InventoryReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryReport extends CreateRecord
{
    protected static string $resource = InventoryReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
