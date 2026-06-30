<?php

namespace App\Filament\Resources\WarehouseZones\Pages;

use App\Filament\Resources\WarehouseZones\WarehouseZoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWarehouseZones extends ListRecords
{
    protected static string $resource = WarehouseZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
