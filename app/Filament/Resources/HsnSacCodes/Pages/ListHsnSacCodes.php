<?php

namespace App\Filament\Resources\HsnSacCodes\Pages;

use App\Filament\Resources\HsnSacCodes\HsnSacCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHsnSacCodes extends ListRecords
{
    protected static string $resource = HsnSacCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
