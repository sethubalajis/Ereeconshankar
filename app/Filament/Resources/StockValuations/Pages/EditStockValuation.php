<?php

namespace App\Filament\Resources\StockValuations\Pages;

use App\Filament\Resources\StockValuations\StockValuationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockValuation extends EditRecord
{
    protected static string $resource = StockValuationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
