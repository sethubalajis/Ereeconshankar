<?php

namespace App\Services;

use App\Contracts\HandlesExpiry;
use App\Models\StatusExpiryLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutomaticStatusExpiryService
{
    /** @var HandlesExpiry[] */
    protected array $handlers = [];

    /**
     * Register an expiry handler.
     */
    public function registerHandler(HandlesExpiry $handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * Run the expiry process across all registered handlers.
     */
    public function processExpiries(): void
    {
        foreach ($this->handlers as $handler) {
            $this->processHandler($handler);
        }
    }

    /**
     * Process expiries for a specific handler.
     */
    protected function processHandler(HandlesExpiry $handler): void
    {
        $query = $handler->getEligibleRecordsQuery();
        
        // Chunk records to prevent high memory usage
        $query->chunkById(100, function ($records) use ($handler) {
            foreach ($records as $record) {
                try {
                    DB::beginTransaction();

                    $result = $handler->processExpiry($record);

                    // Create Audit Log
                    StatusExpiryLog::create([
                        'model_type' => $handler->getModelClass(),
                        'model_id' => $record->id,
                        'previous_status' => $result['previous_status'],
                        'new_status' => $result['new_status'],
                        'deadline_date' => $result['deadline_date'],
                        'reason' => 'Deadline crossed',
                        'processed_by' => 'SYSTEM',
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Failed to process expiry for [{$handler->getModelClass()} - {$record->id}]: " . $e->getMessage(), [
                        'exception' => $e
                    ]);
                }
            }
        });
    }
}
