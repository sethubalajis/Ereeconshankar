<?php

namespace App\Filament\Branch\Resources\PosTerminals\Pages;

use App\Filament\Branch\Resources\PosTerminals\PosTerminalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPosTerminals extends ListRecords
{
    protected static string $resource = PosTerminalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
