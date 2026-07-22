<?php

namespace App\Filament\Branch\Resources\StockReservations\Pages;

use App\Filament\Branch\Resources\StockReservations\StockReservationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockReservation extends EditRecord
{
    protected static string $resource = StockReservationResource::class;

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
