<?php

namespace App\Filament\Branch\Resources\StockTransactions\Pages;

use App\Filament\Branch\Resources\StockTransactions\StockTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockTransaction extends EditRecord
{
    protected static string $resource = StockTransactionResource::class;

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
