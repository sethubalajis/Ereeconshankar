<?php

namespace App\Filament\Resources\BinStocks\Pages;

use App\Filament\Resources\BinStocks\BinStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBinStocks extends ListRecords
{
    protected static string $resource = BinStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
