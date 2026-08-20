<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\CustomerMembership;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomerMembershipHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return CustomerMembership::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return CustomerMembership::query()
            ->where('status', 'Active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay()); // usually memberships expire at the end of the day, or start of the next day
    }

    public function processExpiry(Model $record): array
    {
        /** @var CustomerMembership $record */
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
