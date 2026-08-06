<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class PayrollProcessing extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];


    public function payrollCycle() {
        return $this->belongsTo(PayrollCycle::class, 'payroll_cycle_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public $tds_data_temp = null;

    protected function attendanceSummary(): array
    {
        if (! $this->employee_id || ! $this->payroll_cycle_id) {
            return [
                'total_working_days' => 0,
                'effective_present_days' => 0,
                'missing_attendance_days' => 0,
                'absent_leave_days' => 0,
            ];
        }

        $cycle = $this->payrollCycle;

        if (! $cycle) {
            return [
                'total_working_days' => 0,
                'effective_present_days' => 0,
                'missing_attendance_days' => 0,
                'absent_leave_days' => 0,
            ];
        }

        $startDate = \Carbon\Carbon::parse($cycle->start_date);
        $endDate = \Carbon\Carbon::parse($cycle->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $presentDays = \App\Models\Attendance::where('employee_id', $this->employee_id)
            ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
            ->whereIn('status', ['present', 'Present'])
            ->count();

        $halfDays = \App\Models\Attendance::where('employee_id', $this->employee_id)
            ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
            ->whereIn('status', ['half_day', 'Half Day'])
            ->count();

        $paidHolidayDays = \App\Models\Holiday::whereBetween('date', [$cycle->start_date, $cycle->end_date])
            ->where(function ($query) {
                $query->whereNull('is_restricted')
                    ->orWhere('is_restricted', false);
            })
            ->count();

        $paidLeaveDays = 0;
        $missingAttendanceDays = 0;
        $lopDays = 0;
        $year = \Carbon\Carbon::parse($cycle->start_date)->year;

        foreach (\Carbon\CarbonPeriod::create($cycle->start_date, $cycle->end_date) as $day) {
            $date = $day->toDateString();

            $attendanceStatus = \App\Models\Attendance::where('employee_id', $this->employee_id)
                ->where('date', $date)
                ->value('status');

            if (in_array($attendanceStatus, ['present', 'Present', 'half_day', 'Half Day'], true)) {
                continue;
            }

            $holiday = \App\Models\Holiday::whereDate('date', $date)
                ->where(function ($query) {
                    $query->whereNull('is_restricted')
                        ->orWhere('is_restricted', false);
                })
                ->exists();

            if ($holiday) {
                continue;
            }

            $leave = \App\Models\EmployeeLeave::where('employee_id', $this->employee_id)
                ->where('status', 'approved')
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->first();

            if ($leave) {
                $leaveType = $leave->leaveType;

                if ($this->isCasualLeaveType($leaveType) && $leaveType->days_allowed > 0) {
                    $totalTakenInYear = \DB::table('employee_leaves')
                        ->where('employee_id', $this->employee_id)
                        ->where('status', 'approved')
                        ->where('leave_type_id', $leaveType->id)
                        ->whereYear('start_date', $year)
                        ->get()
                        ->sum(function ($l) {
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
                $missingAttendanceDays++;
                $lopDays++;
            }
        }

        return [
            'total_working_days' => $totalDays,
            'effective_present_days' => $presentDays + ($halfDays * 0.5) + $paidLeaveDays + $paidHolidayDays,
            'missing_attendance_days' => $missingAttendanceDays,
            'absent_leave_days' => $lopDays,
        ];
    }

    public function getTotalWorkingDaysAttribute(): float
    {
        return (float) ($this->attendanceSummary()['total_working_days'] ?? 0);
    }

    public function getEffectivePresentDaysAttribute(): float
    {
        return (float) ($this->attendanceSummary()['effective_present_days'] ?? 0);
    }

    public function getPresentDaysAttribute(): float
    {
        return $this->effective_present_days;
    }

    public function getMissingAttendanceDaysAttribute(): float
    {
        return (float) ($this->attendanceSummary()['missing_attendance_days'] ?? 0);
    }

    public function getAbsentLeaveDaysAttribute(): float
    {
        return (float) ($this->attendanceSummary()['absent_leave_days'] ?? 0);
    }

    protected function isCasualLeaveType($leaveType): bool
    {
        if (! $leaveType) {
            return false;
        }

        $name = strtolower((string) ($leaveType->name ?? ''));
        $code = strtolower((string) ($leaveType->code ?? ''));

        return str_contains($name, 'casual') || $code === 'cl';
    }

    public function calculatePayroll()
    {
        if (!$this->employee_id || !$this->payroll_cycle_id) {
            return;
        }

        $cycle = $this->payrollCycle;
        if (!$cycle) {
            return;
        }

        $assignment = \App\Models\EmployeeSalaryAssignment::where('employee_id', $this->employee_id)
            ->where('effective_from', '<=', $cycle->end_date)
            ->orderBy('effective_from', 'desc')
            ->first();

        if ($assignment) {
            $grossSalary = $assignment->gross_salary;
            $totalDeductions = 0.0;
            
            $startDate = \Carbon\Carbon::parse($cycle->start_date);
            $endDate = \Carbon\Carbon::parse($cycle->end_date);
            $totalDays = $startDate->diffInDays($endDate) + 1;
            
            $presentDays = \App\Models\Attendance::where('employee_id', $this->employee_id)
                ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->whereIn('status', ['present', 'Present'])
                ->count();
                
            $halfDays = \App\Models\Attendance::where('employee_id', $this->employee_id)
                ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->whereIn('status', ['half_day', 'Half Day'])
                ->count();

            $paidHolidayDays = \App\Models\Holiday::whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->where(function ($query) {
                    $query->whereNull('is_restricted')
                        ->orWhere('is_restricted', false);
                })
                ->count();
                
            $paidLeaveDays = 0;
            $missingAttendanceDays = 0;
            $lopDays = 0;
            $presentDaysEquivalent = 0;
            
            $year = \Carbon\Carbon::parse($cycle->start_date)->year;
            $month = \Carbon\Carbon::parse($cycle->start_date)->month;
            
            foreach (\Carbon\CarbonPeriod::create($cycle->start_date, $cycle->end_date) as $day) {
                $date = $day->toDateString();

                $attendanceStatus = \App\Models\Attendance::where('employee_id', $this->employee_id)
                    ->where('date', $date)
                    ->value('status');

                if (in_array($attendanceStatus, ['present', 'Present', 'half_day', 'Half Day'], true)) {
                    continue;
                }

                $holiday = \App\Models\Holiday::whereDate('date', $date)
                    ->where(function ($query) {
                        $query->whereNull('is_restricted')
                            ->orWhere('is_restricted', false);
                    })
                    ->exists();

                if ($holiday) {
                    continue;
                }

                $leave = \App\Models\EmployeeLeave::where('employee_id', $this->employee_id)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->first();
                    
                if ($leave) {
                    $leaveType = $leave->leaveType;
                    if ($this->isCasualLeaveType($leaveType) && $leaveType->days_allowed > 0) {
                        $totalTakenInYear = \DB::table('employee_leaves')
                            ->where('employee_id', $this->employee_id)
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
                    $missingAttendanceDays++;
                    $lopDays++;
                }
            }

            $effectivePresentDays = $presentDays + ($halfDays * 0.5) + $paidLeaveDays + $paidHolidayDays;
            
            $lossOfPay = 0;
            if ($totalDays > 0) {
                $lossOfPay = ($grossSalary / $totalDays) * $lopDays;
            }
            
            $totalDeductions += $lossOfPay;

            // Fetch extra deductions
            $extraDeductions = \App\Models\Deduction::where('employee_id', $this->employee_id)
                ->whereYear('month', $year)
                ->whereMonth('month', $month)
                ->sum('amount');
            $totalDeductions += $extraDeductions;
            
            $bonuses = \App\Models\Bonus::where('employee_id', $this->employee_id)
                ->whereYear('payout_date', $year)
                ->whereMonth('payout_date', $month)
                ->sum('amount');
                
            $incentives = \App\Models\Incentive::where('employee_id', $this->employee_id)
                ->whereYear('month', $year)
                ->whereMonth('month', $month)
                ->sum('amount');

            $reimbursement = \App\Models\Reimbursement::where('employee_id', $this->employee_id)
                ->where('status', 'approved')
                ->whereYear('expense_date', $year)
                ->whereMonth('expense_date', $month)
                ->sum('amount');

            $professionalTaxes = \App\Models\ProfessionalTax::where('employee_id', $this->employee_id)
                ->whereYear('month', $year)
                ->whereMonth('month', $month)
                ->sum('amount');

            // Find Financial Year based on cycle date
            $financialYear = \App\Models\FinancialYear::where('start_date', '<=', $cycle->start_date)
                ->where('end_date', '>=', $cycle->start_date)
                ->first();

            $tdsRecordQuery = \App\Models\TdsRecord::where('employee_id', $this->employee_id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);

            if ($financialYear) {
                $tdsRecordQuery->where('financial_year_id', $financialYear->id);
            }

            $tdsRecord = $tdsRecordQuery->orderByDesc('id')->first();
            $this->tds_records = round((float) ($tdsRecord->monthly_tds ?? 0), 2);
            
            $totalDeductions += $professionalTaxes;
            $totalDeductions += $this->tds_records;

            // Calculate exact breakdown based on FULL gross
            $this->basic_pay = 0;
            $this->hra = 0;
            $this->special_allowance = 0;
            $this->pf = 0;
            $this->esi = 0;

            $structure = $assignment->salaryStructure;
            if ($structure) {
                // Earnings based on Full Gross
                $this->basic_pay = round(($grossSalary * $structure->basic_percentage) / 100, 2);
                $this->hra = round(($grossSalary * $structure->hra_percentage) / 100, 2);
                $this->special_allowance = round(($grossSalary * $structure->special_allowance) / 100, 2);
                
                $proratedGross = $grossSalary - $lossOfPay;
                $proratedBasic = ($proratedGross * $structure->basic_percentage) / 100;
                
                $employee = \App\Models\Employee::find($this->employee_id);
                
                if ($employee && $employee->pf_opt_in) {
                    $pfContribution = \App\Models\PfContribution::where('employee_id', $this->employee_id)
                        ->whereYear('month', $year)
                        ->whereMonth('month', $month)
                        ->first();
                    $this->pf = $pfContribution ? round($pfContribution->employee_share, 2) : 0;
                } else {
                    $this->pf = 0;
                }
                
                if ($employee && $employee->esi_opt_in) {
                    $esiContribution = \App\Models\EsiContribution::where('employee_id', $this->employee_id)
                        ->whereYear('month', $year)
                        ->whereMonth('month', $month)
                        ->first();

                    if ($esiContribution) {
                        $this->esi = round($esiContribution->employee_share, 2);
                    } elseif ($grossSalary <= 21000) {
                        $this->esi = round($grossSalary * 0.0075, 2);
                    } else {
                        $this->esi = 0;
                    }
                } else {
                    $this->esi = 0;
                }
                
                $totalDeductions += $this->pf;
                $totalDeductions += $this->esi;
            }

            $this->loss_of_pay = round($lossOfPay, 2);
            $this->extra_deductions = round($extraDeductions, 2);
            $this->bonuses = round($bonuses, 2);
            
            $this->incentives = round($incentives, 2);
            $this->reimbursement = round($reimbursement, 2);
            $this->professional_taxes = round($professionalTaxes, 2);

            // Gross pay is Full Salary + Bonuses + Incentives + Reimbursement
            $this->gross_pay = round($grossSalary + $bonuses + $incentives + $reimbursement, 2);
            $this->total_deductions = round($totalDeductions, 2);
            $this->net_pay = round($this->gross_pay - $this->total_deductions, 2);

            $annualGross = (float) ($tdsRecord->annual_gross_salary ?? ($grossSalary * 12));
            $approvedDeductions = (float) ($tdsRecord->approved_tax_deductions ?? 0);
            $taxableIncome = (float) ($tdsRecord->taxable_income ?? max(0, $annualGross - $approvedDeductions));
            $annualTax = (float) ($tdsRecord->annual_income_tax ?? ($this->tds_records * 12));
            $taxRegime = (string) ($tdsRecord->tax_regime ?? 'old');
            
            $this->tds_data_temp = [
                'annualGross' => $annualGross,
                'approvedDeductions' => $approvedDeductions,
                'taxableIncome' => $taxableIncome,
                'annualTax' => $annualTax,
                'taxRegime' => $taxRegime,
                'financialYearId' => $financialYear ? $financialYear->id : null,
            ];
        }
    }
    
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->calculatePayroll();
        });
        
        static::saved(function ($model) {
            if (isset($model->tds_data_temp)) {
                \App\Models\TdsRecord::updateOrCreate(
                    ['payroll_processing_id' => $model->id],
                    [
                        'employee_id' => $model->employee_id,
                        'financial_year_id' => $model->tds_data_temp['financialYearId'],
                        'tax_regime' => $model->tds_data_temp['taxRegime'],
                        'annual_gross_salary' => $model->tds_data_temp['annualGross'],
                        'approved_tax_deductions' => $model->tds_data_temp['approvedDeductions'],
                        'taxable_income' => $model->tds_data_temp['taxableIncome'],
                        'annual_income_tax' => $model->tds_data_temp['annualTax'],
                        'monthly_tds' => $model->tds_records,
                        'remaining_tds' => max(0, $model->tds_data_temp['annualTax'] - $model->tds_records),
                        'status' => 'processed',
                    ]
                );
            }
        });
    }
}
