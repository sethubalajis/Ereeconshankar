<?php

namespace App\Filament\Resources\StockTransactionReports\Pages;

use App\Filament\Resources\StockTransactionReports\StockTransactionReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockTransactionReport extends CreateRecord
{
    protected static string $resource = StockTransactionReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
