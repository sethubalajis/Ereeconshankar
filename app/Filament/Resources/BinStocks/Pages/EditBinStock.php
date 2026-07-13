<?php

namespace App\Filament\Resources\BinStocks\Pages;

use App\Filament\Resources\BinStocks\BinStockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBinStock extends EditRecord
{
    protected static string $resource = BinStockResource::class;

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
