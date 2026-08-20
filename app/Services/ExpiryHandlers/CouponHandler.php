<?php

namespace App\Services\ExpiryHandlers;

use App\Contracts\HandlesExpiry;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CouponHandler implements HandlesExpiry
{
    public function getModelClass(): string
    {
        return Coupon::class;
    }

    public function getEligibleRecordsQuery(): Builder
    {
        return Coupon::query()
            ->where('status', 'Active') // Assuming 'Active' or 'active' or 1 based on schema. Most string statuses use Title Case in this ERP. Let's use 'active'.
            ->whereNotNull('end_date')
            ->where('end_date', '<', now());
    }

    public function processExpiry(Model $record): array
    {
        /** @var Coupon $record */
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
