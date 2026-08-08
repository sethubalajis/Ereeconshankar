<?php

namespace App\Filament\Resources\PosSales\Pages;

use App\Filament\Resources\PosSales\PosSaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosSale extends CreateRecord
{
    protected static string $resource = PosSaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            // We invoke the Promotion Engine to validate the final cart state.
            // If the cashier manually selected conflicting offers that violate exclusivity,
            // the engine or checkout service would throw an Exception.
            
            // For now, we simulate a strict check on selected offers if needed.
            // In a full implementation, you would pass $data to PosCheckoutService->processCheckout() here
            // and bypass the default Filament creation, or just validate.
            
            // Example validation:
            if (isset($data['selected_offers']) && count($data['selected_offers']) > 1) {
                // If the engine determines these cannot be combined
                // throw new \Exception('Coupon cannot be combined with Festival Offer.');
            }
            
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Promotion Validation Failed')
                ->body($e->getMessage())
                ->send();

            $this->halt();
        }

        return $data;
    }

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
