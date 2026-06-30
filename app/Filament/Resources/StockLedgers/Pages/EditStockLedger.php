<?php

namespace App\Filament\Resources\StockLedgers\Pages;

use App\Filament\Resources\StockLedgers\StockLedgerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockLedger extends EditRecord
{
    protected static string $resource = StockLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
