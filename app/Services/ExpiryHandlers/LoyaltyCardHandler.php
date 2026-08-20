<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\LoyaltyCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LoyaltyCardHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return LoyaltyCard::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return LoyaltyCard::query()
            ->where('status', 'Active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now()->startOfDay());
    }

    public function processExpiry(Model $record): array
    {
        /** @var LoyaltyCard $record */
        $previousStatus = $record->status;
        $newStatus = 'Expired';
        $deadline = $record->expiry_date;

        $record->status = $newStatus;
        $record->save();

        return [
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'deadline_date' => $deadline,
        ];
    }
}
