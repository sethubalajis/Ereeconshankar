<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollProcessing extends Model
{
    protected $guarded = [];


    public function payrollCycle() {
        return $this->belongsTo(PayrollCycle::class, 'payroll_cycle_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
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
                
            $absentDates = \App\Models\Attendance::where('employee_id', $this->employee_id)
                ->whereBetween('date', [$cycle->start_date, $cycle->end_date])
                ->whereIn('status', ['absent', 'Absent'])
                ->pluck('date');
                
            $paidLeaveDays = 0;
            $lopDays = 0;
            
            $year = \Carbon\Carbon::parse($cycle->start_date)->year;
            $month = \Carbon\Carbon::parse($cycle->start_date)->month;
            
            foreach ($absentDates as $date) {
                // Find if there is an approved leave for this date
                $leave = \App\Models\EmployeeLeave::where('employee_id', $this->employee_id)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->first();
                    
                if ($leave) {
                    $leaveType = $leave->leaveType;
                    if ($leaveType && $leaveType->days_allowed > 0) {
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
                    $lopDays++;
                }
            }

            $effectivePresentDays = $presentDays + ($halfDays * 0.5) + $paidLeaveDays;
            
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

            $tdsRecords = \App\Models\TdsRecord::where('deductee_id', $this->employee_id)
                ->where('deductee_type', \App\Models\Employee::class)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('amount');
                
            $totalDeductions += $professionalTaxes;
            $totalDeductions += $tdsRecords;

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
                
                // PF usually calculated on PRORATED basic
                $proratedGross = $grossSalary - $lossOfPay;
                $proratedBasic = ($proratedGross * $structure->basic_percentage) / 100;
                $this->pf = round($proratedBasic * 0.12, 2); // 12% PF
                
                // ESI is usually 0.75% of PRORATED gross, only if FULL gross <= 21000
                if ($grossSalary <= 21000) {
                    $this->esi = round($proratedGross * 0.0075, 2);
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
            $this->tds_records = round($tdsRecords, 2);

            // Gross pay is Full Salary + Bonuses + Incentives + Reimbursement
            $this->gross_pay = round($grossSalary + $bonuses + $incentives + $reimbursement, 2);
            $this->total_deductions = round($totalDeductions, 2);
            $this->net_pay = round($this->gross_pay - $this->total_deductions, 2);
        }
    }
    
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->calculatePayroll();
        });
    }
}
