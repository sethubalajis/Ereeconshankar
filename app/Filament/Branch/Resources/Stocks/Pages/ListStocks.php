<?php

namespace App\Filament\Branch\Resources\Stocks\Pages;

use App\Filament\Branch\Resources\Stocks\StockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStocks extends ListRecords
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
