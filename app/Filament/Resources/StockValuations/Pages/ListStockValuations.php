<?php

namespace App\Filament\Resources\StockValuations\Pages;

use App\Filament\Resources\StockValuations\StockValuationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockValuations extends ListRecords
{
    protected static string $resource = StockValuationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
