<?php

namespace App\Filament\Resources\PatternTypes\Pages;

use App\Filament\Resources\PatternTypes\PatternTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatternTypes extends ListRecords
{
    protected static string $resource = PatternTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
