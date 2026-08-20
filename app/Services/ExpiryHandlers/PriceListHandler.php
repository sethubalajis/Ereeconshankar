<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\PriceList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PriceListHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return PriceList::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return PriceList::query()
            ->where('status', 1) // 1 = Active for booleans
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay());
    }

    public function processExpiry(Model $record): array
    {
        /** @var PriceList $record */
        $previousStatus = $record->status ? 'Active' : 'Inactive';
        $newStatus = 'Expired'; // Stored as false/0, logged as 'Expired'
        $deadline = $record->end_date;

        $record->status = 0; // Disable it
        $record->save();

        return [
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'deadline_date' => $deadline,
        ];
    }
}
