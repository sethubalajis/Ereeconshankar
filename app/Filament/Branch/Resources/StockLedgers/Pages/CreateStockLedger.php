<?php

namespace App\Filament\Branch\Resources\StockLedgers\Pages;

use App\Filament\Branch\Resources\StockLedgers\StockLedgerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockLedger extends CreateRecord
{
    protected static string $resource = StockLedgerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
