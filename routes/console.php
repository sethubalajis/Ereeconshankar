<?php

use App\Models\PayrollProcessing;
use App\Models\TdsRecord;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tds:backfill-payroll-processing {--dry-run : Show what would be updated without writing changes} {--force : Re-evaluate rows even when payroll_processing_id is already set}', function () {
    $dryRun = (bool) $this->option('dry-run');
    $force = (bool) $this->option('force');

    $query = TdsRecord::query()->orderBy('id');

    if (! $force) {
        $query->whereNull('payroll_processing_id');
    }

    $scanned = 0;
    $matched = 0;
    $updated = 0;
    $unchanged = 0;
    $skipped = 0;

    $query->chunkById(200, function ($records) use ($dryRun, &$scanned, &$matched, &$updated, &$unchanged, &$skipped): void {
        foreach ($records as $record) {
            $scanned++;

            if (! $record->employee_id || ! $record->created_at) {
                $skipped++;
                continue;
            }

            $referenceDate = $record->created_at->toDateString();

            $payrollProcessing = PayrollProcessing::query()
                ->where('employee_id', $record->employee_id)
                ->whereHas('payrollCycle', function ($cycleQuery) use ($referenceDate): void {
                    $cycleQuery->where('start_date', '<=', $referenceDate)
                        ->where('end_date', '>=', $referenceDate);
                })
                ->latest('id')
                ->first();

            if (! $payrollProcessing) {
                $payrollProcessing = PayrollProcessing::query()
                    ->where('employee_id', $record->employee_id)
                    ->whereHas('payrollCycle', function ($cycleQuery) use ($record): void {
                        $cycleQuery
                            ->whereYear('start_date', $record->created_at->year)
                            ->whereMonth('start_date', $record->created_at->month);
                    })
                    ->latest('id')
                    ->first();
            }

            if (! $payrollProcessing) {
                $skipped++;
                continue;
            }

            $matched++;

            if ((int) $record->payroll_processing_id === (int) $payrollProcessing->id) {
                $unchanged++;
                continue;
            }

            if ($dryRun) {
                $updated++;
                continue;
            }

            $record->payroll_processing_id = $payrollProcessing->id;
            $record->save();
            $updated++;
        }
    });

    $this->info('TDS payroll backfill completed.');
    $this->line('Scanned: ' . $scanned);
    $this->line('Matched: ' . $matched);
    $this->line('Updated: ' . $updated . ($dryRun ? ' (dry run)' : ''));
    $this->line('Unchanged: ' . $unchanged);
    $this->line('Skipped: ' . $skipped);
})->purpose('Backfill payroll_processing_id for TDS records based on employee and payroll cycle dates.');

\Illuminate\Support\Facades\Schedule::command('erp:process-expiries')->everyFiveMinutes();
