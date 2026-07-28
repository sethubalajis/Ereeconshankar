<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BiometricAttendanceImporter
{
    public function importFromStoredPath(string $storedPath): array
    {
        $path = Storage::disk('local')->path($storedPath);

        if (! is_file($path)) {
            return [
                'processed' => 0,
                'created' => 0,
                'updated' => 0,
                'unmatched' => 0,
                'skipped' => 1,
            ];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        $result = [
            'processed' => 0,
            'created' => 0,
            'updated' => 0,
            'unmatched' => 0,
            'skipped' => 0,
        ];

        foreach ($lines as $line) {
            $result['processed']++;

            $columns = str_getcsv(trim($line));

            if (count($columns) < 4) {
                $result['skipped']++;
                continue;
            }

            [$dateValue, $biometricCode, $timeValue, $direction] = array_pad($columns, 4, null);

            if (! $dateValue || ! $biometricCode || ! $timeValue || ! $direction) {
                $result['skipped']++;
                continue;
            }

            $employee = Employee::query()
                ->where('biometric_code', (string) $biometricCode)
                ->first();

            if (! $employee) {
                $result['unmatched']++;
                continue;
            }

            $date = Carbon::createFromFormat('m/d/Y', trim((string) $dateValue))->toDateString();
            $time = Carbon::createFromFormat('H:i', trim((string) $timeValue))->format('H:i:s');
            $direction = strtoupper(trim((string) $direction));

            $attendance = Attendance::query()->firstOrNew([
                'employee_id' => $employee->getKey(),
                'date' => $date,
            ]);

            $wasExisting = $attendance->exists;

            if ($direction === 'I') {
                $attendance->check_in = $this->earlierTime($attendance->check_in, $time);
            } elseif ($direction === 'O') {
                $attendance->check_out = $this->laterTime($attendance->check_out, $time);
            } else {
                $result['skipped']++;
                continue;
            }

            $attendance->status = 'present';
            $attendance->save();

            if ($wasExisting) {
                $result['updated']++;
            } else {
                $result['created']++;
            }
        }

        return $result;
    }

    private function earlierTime(?string $currentValue, string $incomingValue): string
    {
        if (! $currentValue) {
            return $incomingValue;
        }

        return strcmp($incomingValue, $currentValue) < 0 ? $incomingValue : $currentValue;
    }

    private function laterTime(?string $currentValue, string $incomingValue): string
    {
        if (! $currentValue) {
            return $incomingValue;
        }

        return strcmp($incomingValue, $currentValue) > 0 ? $incomingValue : $currentValue;
    }
}
