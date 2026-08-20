<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\FestivalOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FestivalOfferHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return FestivalOffer::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return FestivalOffer::query()
            ->where('status', 'Active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay());
    }

    public function processExpiry(Model $record): array
    {
        /** @var FestivalOffer $record */
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
