<?php

namespace App\Filament\Branch\Resources\StockReservations\Pages;

use App\Filament\Branch\Resources\StockReservations\StockReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockReservation extends CreateRecord
{
    protected static string $resource = StockReservationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
