<?php

namespace App\Filament\Resources\StockReservations\Pages;

use App\Filament\Resources\StockReservations\StockReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockReservation extends CreateRecord
{
    protected static string $resource = StockReservationResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            $service = app(\App\Services\StockReservationService::class);
            return $service->reserveStock($data);
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Reservation Failed')
                ->body($e->getMessage())
                ->send();

            throw new \Filament\Support\Exceptions\Halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
