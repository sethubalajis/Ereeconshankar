<?php

namespace App\Filament\Branch\Resources\StockTransactions\Pages;

use App\Filament\Branch\Resources\StockTransactions\StockTransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListStockTransactions extends ListRecords
{
    protected static string $resource = StockTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
