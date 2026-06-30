<?php

namespace App\Filament\Resources\StockLedgers\Pages;

use App\Filament\Resources\StockLedgers\StockLedgerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockLedgers extends ListRecords
{
    protected static string $resource = StockLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
