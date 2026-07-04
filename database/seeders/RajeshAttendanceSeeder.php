<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RajeshAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = \App\Models\Employee::where('first_name', 'LIKE', '%Rajesh%')->first();
        if (!$employee) {
            $employee = \App\Models\Employee::firstOrCreate(
                ['first_name' => 'Rajesh'],
                [
                    'last_name' => 'K',
                    'email' => 'rajesh@example.com',
                    'phone' => '9876543210',
                    'date_of_birth' => '1990-01-01',
                    'gender' => 'male',
                    'hire_date' => '2026-01-01',
                    'employment_status' => 'active',
                ]
            );
        }

        $dates = [];
        for ($i = 1; $i <= 30; $i++) {
            $dates[] = '2026-06-' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $statuses = array_merge(
            array_fill(0, 24, 'present'),
            array_fill(0, 2, 'absent'),
            array_fill(0, 2, 'sick leave'),
            array_fill(0, 2, 'casual leave')
        );
        shuffle($statuses);

        foreach ($dates as $index => $date) {
            $status = $statuses[$index];
            $checkIn = $status === 'present' ? '09:00:00' : null;
            $checkOut = $status === 'present' ? '18:00:00' : null;

            \App\Models\Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $date],
                [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                ]
            );
        }
    }
}
