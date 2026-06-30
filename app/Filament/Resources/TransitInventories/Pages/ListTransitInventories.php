<?php

namespace App\Filament\Resources\TransitInventories\Pages;

use App\Filament\Resources\TransitInventories\TransitInventoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransitInventories extends ListRecords
{
    protected static string $resource = TransitInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
