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
                    ->afterStateUpdated(function ($set, $get, $state) {
                        $employeeId = $get('employee_id');
                        if ($employeeId && $state) {
                            self::updateCalculations($set, $employeeId, $state);
                        }
                    }),
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $get, $state) {
                        self::updateCalculations($set, $state, $get('payroll_cycle_id'));
                    }),
                TextInput::make('basic_pay')->numeric()->default(0.0)->readonly(),
                TextInput::make('hra')->numeric()->default(0.0)->readonly(),
                TextInput::make('special_allowance')->numeric()->default(0.0)->readonly(),
                TextInput::make('pf')->numeric()->default(0.0)->readonly(),
                TextInput::make('esi')->numeric()->default(0.0)->readonly(),
                TextInput::make('loss_of_pay')->numeric()->default(0.0)->readonly(),
                TextInput::make('extra_deductions')->numeric()->default(0.0)->readonly(),
                TextInput::make('bonuses')->numeric()->default(0.0)->readonly(),
                TextInput::make('incentives')->numeric()->default(0.0)->readonly(),
                TextInput::make('reimbursement')->numeric()->default(0.0)->readonly(),
                TextInput::make('professional_taxes')->numeric()->default(0.0)->readonly(),
                TextInput::make('tds_records')->numeric()->default(0.0)->readonly(),
                
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

    public static function updateCalculations($set, $employeeId, $payrollCycleId)
    {
        if (!$employeeId) {
            $set('gross_pay', 0.0);
            $set('total_deductions', 0.0);
            $set('net_pay', 0.0);
            $set('basic_pay', 0.0);
            $set('hra', 0.0);
            $set('special_allowance', 0.0);
            $set('pf', 0.0);
            $set('esi', 0.0);
            $set('loss_of_pay', 0.0);
            $set('extra_deductions', 0.0);
            $set('bonuses', 0.0);
            $set('incentives', 0.0);
            $set('reimbursement', 0.0);
            $set('professional_taxes', 0.0);
            $set('tds_records', 0.0);
            return;
        }

        $cycle = \App\Models\PayrollCycle::find($payrollCycleId);
        if (!$cycle) {
            return;
        }

        $assignment = \App\Models\EmployeeSalaryAssignment::where('employee_id', $employeeId)
            ->where('effective_from', '<=', $cycle->end_date)
            ->orderBy('effective_from', 'desc')
            ->first();

        if ($assignment) {
            $grossSalary = $assignment->gross_salary;
            $totalDeductions = 0.0;
            
            $lossOfPay = 0;
            $extraDeductions = 0;
            $bonuses = 0;

            $startDate = \Carbon\Carbon::parse($cycle->start_date);
            $endDate = \Carbon\Carbon::parse($cycle->end_date);
            $totalDays = $startDate->diffInDays($endDate) + 1;
            
            $presentDays = \App\Models\Attendance::where('employee_id', $employeeId)
                ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->whereIn('status', ['present', 'Present'])
                ->count();
                
            $halfDays = \App\Models\Attendance::where('employee_id', $employeeId)
                ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->whereIn('status', ['half_day', 'Half Day'])
                ->count();
            
            $absentDates = \App\Models\Attendance::where('employee_id', $employeeId)
                ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->whereIn('status', ['absent', 'Absent'])
                ->pluck('date');

            $paidLeaveDays = 0;
            $lopDays = 0;
            $year = \Carbon\Carbon::parse($cycle->start_date)->year;
            $month = \Carbon\Carbon::parse($cycle->start_date)->month;

            foreach ($absentDates as $date) {
                $leave = \App\Models\EmployeeLeave::where('employee_id', $employeeId)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->first();

                if ($leave) {
                    $leaveType = $leave->leaveType;
                    if ($leaveType && $leaveType->days_allowed > 0) {
                        $totalTakenInYear = \DB::table('employee_leaves')
                            ->where('employee_id', $employeeId)
                            ->where('status', 'approved')
                            ->where('leave_type_id', $leaveType->id)
                            ->whereYear('start_date', $year)
                            ->get()
                            ->sum(function($l) {
                                return \Carbon\Carbon::parse($l->start_date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1;
                            });

                        if ($totalTakenInYear <= $leaveType->days_allowed) {
                            $paidLeaveDays++;
                        } else {
                            $lopDays++;
                        }
                    } else {
                        $lopDays++;
                    }
                } else {
                    $lopDays++;
                }
            }
            
            if ($totalDays > 0) {
                $lossOfPay = ($grossSalary / $totalDays) * $lopDays;
            }

            $totalDeductions += $lossOfPay;

            $extraDeductions = \App\Models\Deduction::where('employee_id', $employeeId)
                ->whereYear('month', $year)
                ->whereMonth('month', $month)
                ->sum('amount');
            $totalDeductions += $extraDeductions;
            
            $bonuses = \App\Models\Bonus::where('employee_id', $employeeId)
                ->whereYear('payout_date', $year)
                ->whereMonth('payout_date', $month)
                ->sum('amount');
                
            $incentives = \App\Models\Incentive::where('employee_id', $employeeId)
                ->whereYear('month', $year)
                ->whereMonth('month', $month)
                ->sum('amount');

            $reimbursement = \App\Models\Reimbursement::where('employee_id', $employeeId)
                ->where('status', 'approved')
                ->whereYear('expense_date', $year)
                ->whereMonth('expense_date', $month)
                ->sum('amount');

            $professionalTaxes = \App\Models\ProfessionalTax::where('employee_id', $employeeId)
                ->whereYear('month', $year)
                ->whereMonth('month', $month)
                ->sum('amount');

            $tdsRecords = \App\Models\TdsRecord::where('deductee_id', $employeeId)
                ->where('deductee_type', \App\Models\Employee::class)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('amount');
                
            $totalDeductions += $professionalTaxes;
            $totalDeductions += $tdsRecords;
        
            $basicPay = 0;
            $hra = 0;
            $specialAllowance = 0;
            $pf = 0;
            $esi = 0;
            
            $structure = $assignment->salaryStructure;
            if ($structure) {
                $basicPay = ($grossSalary * $structure->basic_percentage) / 100;
                $hra = ($grossSalary * $structure->hra_percentage) / 100;
                $specialAllowance = ($grossSalary * $structure->special_allowance) / 100;
                
                $proratedGross = $grossSalary - $lossOfPay;
                $proratedBasic = ($proratedGross * $structure->basic_percentage) / 100;
                $pf = $proratedBasic * 0.12; 
                
                if ($grossSalary <= 21000) {
                    $esi = $proratedGross * 0.0075;
                } else {
                    $esi = 0;
                }
                
                $totalDeductions += $pf;
                $totalDeductions += $esi;
            }

            $set('basic_pay', round($basicPay, 2));
            $set('hra', round($hra, 2));
            $set('special_allowance', round($specialAllowance, 2));
            $set('pf', round($pf, 2));
            $set('esi', round($esi, 2));
            $set('loss_of_pay', round($lossOfPay ?? 0, 2));
            $set('extra_deductions', round($extraDeductions ?? 0, 2));
            $set('bonuses', round($bonuses ?? 0, 2));
            $set('incentives', round($incentives ?? 0, 2));
            $set('reimbursement', round($reimbursement ?? 0, 2));
            $set('professional_taxes', round($professionalTaxes ?? 0, 2));
            $set('tds_records', round($tdsRecords ?? 0, 2));

            $grossPayFinal = $grossSalary + ($bonuses ?? 0) + ($incentives ?? 0) + ($reimbursement ?? 0);
            $set('gross_pay', round($grossPayFinal, 2));
            $set('total_deductions', round($totalDeductions, 2));
            $set('net_pay', round($grossPayFinal - $totalDeductions, 2));
        }
    }
}
