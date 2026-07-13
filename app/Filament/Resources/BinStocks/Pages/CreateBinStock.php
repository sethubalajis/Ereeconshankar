<?php

namespace App\Filament\Resources\BinStocks\Pages;

use App\Filament\Resources\BinStocks\BinStockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBinStock extends CreateRecord
{
    protected static string $resource = BinStockResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
