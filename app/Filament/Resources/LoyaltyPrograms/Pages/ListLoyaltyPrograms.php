<?php

namespace App\Filament\Resources\LoyaltyPrograms\Pages;

use App\Filament\Resources\LoyaltyPrograms\LoyaltyProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoyaltyPrograms extends ListRecords
{
    protected static string $resource = LoyaltyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
