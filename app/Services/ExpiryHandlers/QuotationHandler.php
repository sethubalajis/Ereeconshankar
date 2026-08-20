<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuotationHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return Quotation::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return Quotation::query()
            ->whereIn('status', ['Open', 'Pending', 'Draft'])
            ->whereNotNull('validity_date')
            ->where('validity_date', '<', now()->startOfDay());
    }

    public function processExpiry(Model $record): array
    {
        /** @var Quotation $record */
        $previousStatus = $record->status;
        $newStatus = 'Expired';
        $deadline = $record->validity_date;

        $record->status = $newStatus;
        $record->save();

        return [
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'deadline_date' => $deadline,
        ];
    }
}
