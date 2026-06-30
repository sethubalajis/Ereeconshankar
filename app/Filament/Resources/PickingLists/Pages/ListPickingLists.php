<?php

namespace App\Filament\Resources\PickingLists\Pages;

use App\Filament\Resources\PickingLists\PickingListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPickingLists extends ListRecords
{
    protected static string $resource = PickingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
