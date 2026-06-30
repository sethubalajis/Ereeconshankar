<?php

namespace App\Filament\Resources\MerchandisePlannings\Pages;

use App\Filament\Resources\MerchandisePlannings\MerchandisePlanningResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMerchandisePlannings extends ListRecords
{
    protected static string $resource = MerchandisePlanningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
