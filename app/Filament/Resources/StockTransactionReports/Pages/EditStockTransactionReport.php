<?php

namespace App\Filament\Resources\StockTransactionReports\Pages;

use App\Filament\Resources\StockTransactionReports\StockTransactionReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockTransactionReport extends EditRecord
{
    protected static string $resource = StockTransactionReportResource::class;

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
