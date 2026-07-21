<?php

namespace App\Filament\Branch\Resources\StockLedgers\Pages;

use App\Filament\Branch\Resources\StockLedgers\StockLedgerResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
