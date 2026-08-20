<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\ComboOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ComboOfferHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return ComboOffer::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return ComboOffer::query()
            ->where('status', 'Active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay());
    }

    public function processExpiry(Model $record): array
    {
        /** @var ComboOffer $record */
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
