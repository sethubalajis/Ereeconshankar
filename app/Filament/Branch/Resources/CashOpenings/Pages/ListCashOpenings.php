<?php

namespace App\Filament\Branch\Resources\CashOpenings\Pages;

use App\Filament\Branch\Resources\CashOpenings\CashOpeningResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCashOpenings extends ListRecords
{
    protected static string $resource = CashOpeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
