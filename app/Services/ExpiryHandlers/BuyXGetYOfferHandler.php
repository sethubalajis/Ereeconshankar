<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\BuyXGetYOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BuyXGetYOfferHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return BuyXGetYOffer::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return BuyXGetYOffer::query()
            ->where('status', 'Active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay());
    }

    public function processExpiry(Model $record): array
    {
        /** @var BuyXGetYOffer $record */
        $previousStatus = $record->status;
        $newStatus = 'Expired';
        $deadline = $record->end_date;

        $record->status = $newStatus;
        $record->save();

        return [
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'deadline_date' => $deadline,
        ];
    }
}
