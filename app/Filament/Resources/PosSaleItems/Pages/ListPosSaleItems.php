<?php

namespace App\Filament\Resources\PosSaleItems\Pages;

use App\Filament\Resources\PosSaleItems\PosSaleItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosSaleItems extends ListRecords
{
    protected static string $resource = PosSaleItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
