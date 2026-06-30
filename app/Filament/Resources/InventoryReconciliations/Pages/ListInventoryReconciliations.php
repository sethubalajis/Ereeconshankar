<?php

namespace App\Filament\Resources\InventoryReconciliations\Pages;

use App\Filament\Resources\InventoryReconciliations\InventoryReconciliationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryReconciliations extends ListRecords
{
    protected static string $resource = InventoryReconciliationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
