<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\StockReservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StockReservationHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return StockReservation::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return StockReservation::query()
            ->whereIn('status', [StockReservation::STATUS_PENDING, StockReservation::STATUS_RESERVED])
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }

    public function processExpiry(Model $record): array
    {
        /** @var StockReservation $record */
        $previousStatus = $record->status;
        $newStatus = StockReservation::STATUS_EXPIRED;
        $deadline = $record->expiry_date;

        // Change status to trigger the observer (which releases the stock)
        $record->status = $newStatus;
        $record->save();

        return [
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'deadline_date' => $deadline,
        ];
    }
}
