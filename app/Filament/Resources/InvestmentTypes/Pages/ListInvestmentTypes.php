<?php

namespace App\Filament\Resources\InvestmentTypes\Pages;

use App\Filament\Resources\InvestmentTypes\InvestmentTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInvestmentTypes extends ListRecords
{
    protected static string $resource = InvestmentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
