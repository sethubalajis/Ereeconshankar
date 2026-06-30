<?php

namespace App\Filament\Resources\ContraEntries\Pages;

use App\Filament\Resources\ContraEntries\ContraEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContraEntries extends ListRecords
{
    protected static string $resource = ContraEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
