<?php

namespace App\Filament\Resources\PackingLists\Pages;

use App\Filament\Resources\PackingLists\PackingListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPackingLists extends ListRecords
{
    protected static string $resource = PackingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
