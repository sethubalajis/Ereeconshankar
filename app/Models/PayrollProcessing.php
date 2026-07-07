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

        $assignment = \App\Models\EmployeeSalaryAssignment::where('employee_id', $this->employee_id)
            ->orderBy('effective_from', 'desc')
            ->first();
            
        $cycle = $this->payrollCycle;

        if ($assignment && $cycle) {
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
                        // Count how many paid leaves of this type taken in the year BEFORE this date
                        $takenSoFar = \App\Models\EmployeeLeave::where('employee_id', $this->employee_id)
                            ->where('status', 'approved')
                            ->where('leave_type_id', $leaveType->id)
                            ->whereYear('start_date', $year)
                            ->where('start_date', '<', $date)
                            ->sum(\DB::raw('DATEDIFF(LEAST(end_date, "' . clone(\Carbon\Carbon::parse($date))->subDay()->format('Y-m-d') . '"), GREATEST(start_date, "' . $year . '-01-01")) + 1'));
                        
                        // Simplify by roughly tracking within the loop
                        // A better way is to just query how many days taken for this type in the year
                        // Let's keep it simple: how many days approved this year?
                        // If we are strictly checking if this specific day exceeds the limit:
                        // Actually, calculating exact days taken before this date is complex in raw SQL, let's just query total approved days in year for this type.
                        $totalTakenInYear = \DB::table('employee_leaves')
                            ->where('employee_id', $this->employee_id)
                            ->where('status', 'approved')
                            ->where('leave_type_id', $leaveType->id)
                            ->whereYear('start_date', $year)
                            ->get()
                            ->sum(function($l) {
                                return \Carbon\Carbon::parse($l->start_date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1;
                            });
                            
                        // Since this is evaluated at the end of the month, if total taken > allowed, we might over-penalize. 
                        // But for simplicity, we assume if they exceeded their yearly allowance, we just charge LOP.
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
                // If there are days unaccounted for in attendance, should we count them as LOP or ignore?
                // Usually we just base LOP on $lopDays + (unaccounted days if they are working days)
                // Let's just use $lopDays for actual absent LOP.
                $lossOfPay = ($grossSalary / $totalDays) * $lopDays;
            }
            
            $totalDeductions += $lossOfPay;

            // Fetch extra deductions
            $extraDeductions = \App\Models\Deduction::where('employee_id', $this->employee_id)
                ->whereBetween('month', [$cycle->start_date, $cycle->end_date])
                ->sum('amount');
            $totalDeductions += $extraDeductions;
            
            // Calculate PF if structure exists (PF is based on full basic or prorated basic?)
            // Usually PF is on prorated basic, let's use prorated basic
            $proratedGross = $grossSalary - $lossOfPay;
            $structure = $assignment->salaryStructure;
            if ($structure) {
                $basic = ($proratedGross * $structure->basic_percentage) / 100;
                $pf = $basic * 0.12; // 12% PF
                $totalDeductions += $pf;
            }

            // Fetch bonuses
            $bonuses = \App\Models\Bonus::where('employee_id', $this->employee_id)
                ->whereBetween('payout_date', [$cycle->start_date, $cycle->end_date])
                ->sum('amount');

            // Gross pay is Full Salary + Bonuses
            $this->gross_pay = round($grossSalary + $bonuses, 2);
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
