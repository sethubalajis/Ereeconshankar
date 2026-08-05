<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockReservation;
use App\Models\StockTransaction;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use Exception;

class StockReservationService
{
    /**
     * Create a new stock reservation.
     */
    public function reserveStock(array $data)
    {
        return DB::transaction(function () use ($data) {
            $stock = Stock::where([
                'product_id' => $data['product_id'],
                'product_variant_id' => $data['product_variant_id'] ?? null,
                'branch_id' => $data['branch_id'],
                'warehouse_id' => $data['warehouse_id'] ?? null,
            ])->lockForUpdate()->first();

            if (!$stock) {
                throw new Exception("Stock not found for the given criteria.");
            }

            // Using quantity as on_hand_qty, as decided.
            $availableQty = $stock->quantity - $stock->reserved_qty - $stock->damaged_qty;
            if ($availableQty < $data['quantity']) {
                throw new Exception("Requested quantity exceeds available stock. Available: {$availableQty}");
            }

            // Create Reservation
            $reservation = StockReservation::create([
                'reservation_number' => $data['reservation_number'] ?? $this->generateReservationNumber(),
                'product_id' => $data['product_id'],
                'product_variant_id' => $data['product_variant_id'] ?? null,
                'branch_id' => $data['branch_id'],
                'company_id' => $data['company_id'],
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'quantity' => $data['quantity'],
                'reservation_type' => $data['reservation_type'],
                'reference_document' => $data['reference_document'] ?? null,
                'reservation_date' => $data['reservation_date'] ?? now(),
                'expiry_date' => $data['expiry_date'] ?? null,
                'status' => StockReservation::STATUS_RESERVED,
                'created_by' => auth()->id() ?? $data['created_by'] ?? null,
            ]);

            // Save Before States
            $onHandBefore = $stock->quantity;
            $reservedBefore = $stock->reserved_qty;
            $availableBefore = $onHandBefore - $reservedBefore - $stock->damaged_qty;

            // Update Stock
            $stock->reserved_qty += $data['quantity'];
            $stock->available_qty = $stock->quantity - $stock->reserved_qty - $stock->damaged_qty;
            $stock->save();

            // Record Transaction
            // The business rule explicitly states: "Creating a Stock Reservation must not create a Stock Transaction."
            // $this->recordTransaction($stock, $reservation, 'Stock Reservation', $onHandBefore, $reservedBefore, $availableBefore);

            return $reservation;
        });
    }

    /**
     * Complete a reservation (Delivery/Invoice/Transfer Dispatch)
     */
    public function completeReservation(StockReservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            if ($reservation->status !== StockReservation::STATUS_RESERVED) {
                throw new Exception("Cannot complete a reservation that is not in Reserved status.");
            }

            $stock = Stock::where([
                'product_id' => $reservation->product_id,
                'product_variant_id' => $reservation->product_variant_id,
                'branch_id' => $reservation->branch_id,
                'warehouse_id' => $reservation->warehouse_id,
            ])->lockForUpdate()->first();

            if ($stock) {
                $onHandBefore = $stock->quantity;
                $reservedBefore = $stock->reserved_qty;
                $availableBefore = $onHandBefore - $reservedBefore - $stock->damaged_qty;

                $stock->quantity -= $reservation->quantity;
                $stock->reserved_qty -= $reservation->quantity;
                $stock->available_qty = $stock->quantity - $stock->reserved_qty - $stock->damaged_qty;
                $stock->save();

                $this->recordTransaction($stock, $reservation, 'Stock Issue', $onHandBefore, $reservedBefore, $availableBefore);
            }

            $reservation->update(['status' => StockReservation::STATUS_COMPLETED]);

            return $reservation;
        });
    }

    /**
     * Cancel a reservation manually
     */
    public function cancelReservation(StockReservation $reservation)
    {
        return $this->releaseReservation($reservation, StockReservation::STATUS_RELEASED);
    }
    
    /**
     * Expire a reservation automatically
     */
    public function expireReservation(StockReservation $reservation)
    {
        return $this->releaseReservation($reservation, StockReservation::STATUS_EXPIRED);
    }

    private function releaseReservation(StockReservation $reservation, string $status)
    {
        return DB::transaction(function () use ($reservation, $status) {
            if ($reservation->status !== StockReservation::STATUS_RESERVED && $reservation->status !== StockReservation::STATUS_PENDING) {
                throw new Exception("Only Pending or Reserved reservations can be released.");
            }

            $stock = Stock::where([
                'product_id' => $reservation->product_id,
                'product_variant_id' => $reservation->product_variant_id,
                'branch_id' => $reservation->branch_id,
                'warehouse_id' => $reservation->warehouse_id,
            ])->lockForUpdate()->first();

            if ($stock) {
                $onHandBefore = $stock->quantity;
                $reservedBefore = $stock->reserved_qty;
                $availableBefore = $onHandBefore - $reservedBefore - $stock->damaged_qty;

                $stock->reserved_qty -= $reservation->quantity;
                $stock->available_qty = $stock->quantity - $stock->reserved_qty - $stock->damaged_qty;
                $stock->save();

                // Business rule: No Stock Transaction should be created because no physical stock movement occurred upon expiry/release.
                // $this->recordTransaction($stock, $reservation, 'Reservation Release', $onHandBefore, $reservedBefore, $availableBefore);
            }

            $reservation->update(['status' => $status]);

            return $reservation;
        });
    }

    private function recordTransaction($stock, $reservation, $transactionType, $onHandBefore, $reservedBefore, $availableBefore)
    {
        StockTransaction::create([
            'stock_id' => $stock->id,
            'transaction_type' => $transactionType,
            'quantity' => $reservation->quantity,
            'reference' => $reservation->id,
            'reference_type' => StockReservation::class,
            
            'company_id' => $reservation->company_id,
            'branch_id' => $reservation->branch_id,
            'warehouse_id' => $reservation->warehouse_id,
            'product_id' => $reservation->product_id,
            'user_id' => auth()->id() ?? $reservation->created_by,
            
            'on_hand_before' => $onHandBefore,
            'on_hand_after' => $stock->quantity,
            'reserved_before' => $reservedBefore,
            'reserved_after' => $stock->reserved_qty,
            'available_before' => $availableBefore,
            'available_after' => $stock->available_qty,
            
            'transaction_date' => now(),
        ]);

        StockLedger::create([
            'product_id' => $reservation->product_id,
            'product_variant_id' => $reservation->product_variant_id,
            'branch_id' => $reservation->branch_id,
            'warehouse_id' => $reservation->warehouse_id,
            'transaction_date' => now()->format('Y-m-d'),
            'opening_qty' => $onHandBefore,
            'inward_qty' => 0,
            'outward_qty' => $transactionType === 'Stock Issue' ? $reservation->quantity : 0,
            'closing_qty' => $stock->quantity,
        ]);
    }

    private function generateReservationNumber()
    {
        $prefix = 'RES-';
        $lastReservation = StockReservation::orderBy('id', 'desc')->first();
        if (!$lastReservation || !$lastReservation->reservation_number) {
            return $prefix . '000001';
        }
        $number = (int) str_replace($prefix, '', $lastReservation->reservation_number);
        return $prefix . str_pad($number + 1, 6, '0', STR_PAD_LEFT);
    }
}

