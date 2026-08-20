<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\DiscountRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DiscountRuleHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return DiscountRule::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return DiscountRule::query()
            ->where('status', 'Active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay());
    }

    public function processExpiry(Model $record): array
    {
        /** @var DiscountRule $record */
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
