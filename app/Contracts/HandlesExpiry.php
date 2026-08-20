<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface HandlesExpiry
{
    /**
     * Get the fully qualified class name of the model this handler processes.
     */
    public function getModelClass(): string;

    /**
     * Get a query builder instance that returns all currently eligible records 
     * that should be expired. This should filter by the correct active statuses
     * and check if the deadline date has passed according to application timezone.
     */
    public function getEligibleRecordsQuery(): Builder;

    /**
     * Process the expiry for a single record.
     * This method must:
     * 1. Change the status to the expired status.
     * 2. Perform any model-specific side-effects (e.g. release stock).
     * 3. Return the old status and the new status for logging purposes.
     * 
     * @return array{previous_status: string, new_status: string, deadline_date: \Carbon\Carbon|null}
     */
    public function processExpiry(Model $record): array;
}
