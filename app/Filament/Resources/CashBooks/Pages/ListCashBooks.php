<?php

namespace App\Filament\Resources\CashBooks\Pages;

use App\Filament\Resources\CashBooks\CashBookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCashBooks extends ListRecords
{
    protected static string $resource = CashBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
