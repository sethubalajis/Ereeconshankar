<?php

namespace App\Filament\Resources\FabricTypes\Pages;

use App\Filament\Resources\FabricTypes\FabricTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFabricTypes extends ListRecords
{
    protected static string $resource = FabricTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
