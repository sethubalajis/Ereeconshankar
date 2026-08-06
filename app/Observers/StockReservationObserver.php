<?php

namespace App\Observers;

use App\Models\StockReservation;
use App\Models\Stock;

class StockReservationObserver
{
    /**
     * Handle the StockReservation "created" event.
     */
    public function created(StockReservation $stockReservation): void
    {
        // When a reservation is created, find the corresponding stock and reserve it.
        if (in_array($stockReservation->status, [StockReservation::STATUS_PENDING, StockReservation::STATUS_RESERVED])) {
            $stock = $this->findStock($stockReservation);
            if ($stock) {
                $stock->reserve($stockReservation->quantity);
            }
        }
    }

    /**
     * Handle the StockReservation "updated" event.
     */
    public function updated(StockReservation $stockReservation): void
    {
        if ($stockReservation->wasChanged('status')) {
            $newStatus = $stockReservation->status;
            $oldStatus = $stockReservation->getOriginal('status');

            $stock = $this->findStock($stockReservation);
            if ($stock) {
                $activeStatuses = [StockReservation::STATUS_PENDING, StockReservation::STATUS_RESERVED];
                $inactiveStatuses = [StockReservation::STATUS_RELEASED, StockReservation::STATUS_EXPIRED, StockReservation::STATUS_COMPLETED];

                if (in_array($oldStatus, $activeStatuses) && in_array($newStatus, $inactiveStatuses)) {
                    // It was active, now it's inactive (e.g. expired, completed, released)
                    
                    if ($newStatus === StockReservation::STATUS_COMPLETED) {
                        $stock->reserved_qty -= $stockReservation->quantity;
                        $stock->available_qty = $stock->quantity - $stock->reserved_qty - ($stock->damaged_qty ?? 0);
                        $stock->save();
                    } else {
                        // For released/expired, physical qty doesn't change, so we must increase available_qty
                        $stock->release($stockReservation->quantity);
                    }
                } elseif (in_array($oldStatus, $inactiveStatuses) && in_array($newStatus, $activeStatuses)) {
                    // It was inactive, now it's active
                    $stock->reserve($stockReservation->quantity);
                }
            }
        }
    }

    /**
     * Handle the StockReservation "deleted" event.
     */
    public function deleted(StockReservation $stockReservation): void
    {
        if (in_array($stockReservation->status, [StockReservation::STATUS_PENDING, StockReservation::STATUS_RESERVED])) {
            $stock = $this->findStock($stockReservation);
            if ($stock) {
                $stock->release($stockReservation->quantity);
            }
        }
    }

    private function findStock(StockReservation $stockReservation)
    {
        // First try to find exact match
        $stock = Stock::query()
            ->where('product_id', $stockReservation->product_id)
            ->where('product_variant_id', $stockReservation->product_variant_id)
            ->where('branch_id', $stockReservation->branch_id)
            ->where('warehouse_id', $stockReservation->warehouse_id)
            ->first();

        // If warehouse was null or not found, try to find any stock in branch
        if (!$stock) {
            $stock = Stock::query()
                ->where('product_id', $stockReservation->product_id)
                ->where('product_variant_id', $stockReservation->product_variant_id)
                ->where('branch_id', $stockReservation->branch_id)
                ->orderByRaw('warehouse_id IS NULL DESC')
                ->first();
        }
        
        return $stock;
    }
}
