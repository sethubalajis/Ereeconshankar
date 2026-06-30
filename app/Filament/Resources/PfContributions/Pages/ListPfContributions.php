<?php

namespace App\Filament\Resources\PfContributions\Pages;

use App\Filament\Resources\PfContributions\PfContributionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPfContributions extends ListRecords
{
    protected static string $resource = PfContributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
