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
            // Validate Stock for all items before attempting to create anything
            $items = $this->data['posSaleItems'] ?? [];
            $branchId = $data['branch_id'] ?? null;

            foreach ($items as $item) {
                $productId = $item['product_id'] ?? null;
                if (!$productId || !$branchId) {
                    continue;
                }

                $variantId = $item['product_variant_id'] ?? null;
                $quantity = (float) ($item['quantity'] ?? 0);
                
                if ($quantity <= 0) continue;

                $stockQuery = \App\Models\Stock::where('product_id', $productId)
                    ->where('branch_id', $branchId);

                if ($variantId) {
                    $stockQuery->where('product_variant_id', $variantId);
                } else {
                    $stockQuery->whereNull('product_variant_id');
                }


                $available = (float) $stockQuery->sum('available_qty');

                $reservationId = $data['stock_reservation_id'] ?? null;
                if ($reservationId) {
                    $res = \App\Models\StockReservation::find($reservationId);
                    if ($res && $res->product_id == $productId && $res->product_variant_id == $variantId) {
                        $available += $res->quantity;
                    }
                }

                if ($available < $quantity) {
                    $productName = \App\Models\Product::find($productId)?->name ?? 'Product';
                    throw new \Exception("Insufficient available stock for {$productName}. Available: {$available}, Requested: {$quantity}");
                }
            }

            // Example validation:
            if (isset($data['selected_offers']) && count($data['selected_offers']) > 1) {
                // If the engine determines these cannot be combined
                // throw new \Exception('Coupon cannot be combined with Festival Offer.');
            }
            
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Validation Failed')
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
