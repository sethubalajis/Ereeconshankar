<?php

namespace App\Filament\Branch\Resources\Bonuses\Pages;

use App\Filament\Branch\Resources\Bonuses\BonusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBonuses extends ListRecords
{
    protected static string $resource = BonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
