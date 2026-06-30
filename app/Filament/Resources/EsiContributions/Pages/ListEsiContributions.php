<?php

namespace App\Filament\Resources\EsiContributions\Pages;

use App\Filament\Resources\EsiContributions\EsiContributionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEsiContributions extends ListRecords
{
    protected static string $resource = EsiContributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
