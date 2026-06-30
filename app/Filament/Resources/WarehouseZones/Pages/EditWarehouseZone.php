<?php

namespace App\Filament\Resources\WarehouseZones\Pages;

use App\Filament\Resources\WarehouseZones\WarehouseZoneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWarehouseZone extends EditRecord
{
    protected static string $resource = WarehouseZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
