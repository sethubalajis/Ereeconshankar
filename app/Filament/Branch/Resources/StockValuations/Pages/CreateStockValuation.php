<?php

namespace App\Filament\Branch\Resources\StockValuations\Pages;

use App\Filament\Branch\Resources\StockValuations\StockValuationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockValuation extends CreateRecord
{
    protected static string $resource = StockValuationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
