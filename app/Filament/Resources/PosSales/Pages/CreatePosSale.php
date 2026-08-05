<?php

namespace App\Filament\Resources\PosSales\Pages;

use App\Filament\Resources\PosSales\PosSaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosSale extends CreateRecord
{
    protected static string $resource = PosSaleResource::class;

    protected function afterCreate(): void
    {
        $sale = $this->record;
        
        if ($sale->status === 'completed') {
            foreach ($sale->posSaleItems as $item) {
                $item->processStockDeduction();
            }
            
            // Mark associated stock reservation as completed
            if ($sale->stock_reservation_id) {
                $reservation = \App\Models\StockReservation::find($sale->stock_reservation_id);
                if ($reservation && $reservation->status !== \App\Models\StockReservation::STATUS_COMPLETED) {
                    $reservation->update(['status' => \App\Models\StockReservation::STATUS_COMPLETED]);
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
