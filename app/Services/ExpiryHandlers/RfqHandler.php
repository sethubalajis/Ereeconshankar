<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\Rfq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RfqHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return Rfq::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return Rfq::query()
            ->whereIn('status', ['Open', 'Published', 'Pending'])
            ->whereNotNull('submission_deadline')
            ->where('submission_deadline', '<', now());
    }

    public function processExpiry(Model $record): array
    {
        /** @var Rfq $record */
        $previousStatus = $record->status;
        $newStatus = 'Closed'; // Usually RFQs close when the deadline is passed, rather than "Expired"
        $deadline = $record->submission_deadline;

        $record->status = $newStatus;
        $record->save();

        return [
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'deadline_date' => $deadline,
        ];
    }
}
