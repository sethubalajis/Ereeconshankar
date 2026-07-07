<?php

namespace App\Filament\Resources\PayrollProcessings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollProcessingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('payroll_cycle_id')->relationship('payrollCycle', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $get) {
                        $employeeId = $get('employee_id');
                        if ($employeeId) {
                            $set('employee_id', $employeeId); // trigger update
                        }
                    }),
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        if (!$state) {
                            $set('gross_pay', 0.0);
                            $set('total_deductions', 0.0);
                            $set('net_pay', 0.0);
                            return;
                        }
                        $assignment = \App\Models\EmployeeSalaryAssignment::where('employee_id', $state)->orderBy('effective_from', 'desc')->first();
                        $payrollCycleId = $get('payroll_cycle_id');
                        $cycle = \App\Models\PayrollCycle::find($payrollCycleId);

                        if ($assignment) {
                            $grossSalary = $assignment->gross_salary;
                            $totalDeductions = 0.0;
                            
                            if ($cycle) {
                                $startDate = \Carbon\Carbon::parse($cycle->start_date);
                                $endDate = \Carbon\Carbon::parse($cycle->end_date);
                                $totalDays = $startDate->diffInDays($endDate) + 1;
                                
                                $presentDays = \App\Models\Attendance::where('employee_id', $state)
                                    ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                                    ->where('status', 'Present')
                                    ->count();
                                
                                if ($totalDays > 0) {
                                    $grossSalary = ($grossSalary / $totalDays) * $presentDays;
                                }

                                // Fetch extra deductions
                                $extraDeductions = \App\Models\Deduction::where('employee_id', $state)
                                    ->whereBetween('month', [$cycle->start_date, $cycle->end_date])
                                    ->sum('amount');
                                $totalDeductions += $extraDeductions;
                            }
                            
                            // Calculate PF if structure exists
                            $structure = $assignment->salaryStructure;
                            if ($structure) {
                                $basic = ($grossSalary * $structure->basic_percentage) / 100;
                                $pf = $basic * 0.12; // 12% PF
                                $totalDeductions += $pf;
                            }

                            $set('gross_pay', round($grossSalary, 2));
                            $set('total_deductions', round($totalDeductions, 2));
                            $set('net_pay', round($grossSalary - $totalDeductions, 2));
                        }
                    }),
                TextInput::make('gross_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $get) {
                        $set('net_pay', (float) $get('gross_pay') - (float) $get('total_deductions'));
                    }),
                TextInput::make('total_deductions')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $get) {
                        $set('net_pay', (float) $get('gross_pay') - (float) $get('total_deductions'));
                    }),

                TextInput::make('net_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readonly(),
                TextInput::make('status')
                    ->required()
                    ->default('processed'),
            ]);
    }
}
