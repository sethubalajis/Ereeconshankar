<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $record->employee->first_name }} {{ $record->employee->last_name }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; color: #1f2937; }
        .header p { margin: 5px 0; color: #6b7280; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; }
        .totals { margin-top: 30px; font-weight: bold; }
        .totals td { background-color: #f3f4f6; }
        .text-right { text-align: right; }
        .company-name { font-size: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">E-Reckon Technologies</div>
        <p>123 Business Avenue, Tech District, City</p>
        <h1>Payslip</h1>
        <p>For the period: {{ $record->payrollCycle->name ?? 'N/A' }}</p>
    </div>

    <div class="section-title">Employee Details</div>
    <table>
        <tr>
            <th>Employee Name</th>
            <td>{{ $record->employee->first_name }} {{ $record->employee->last_name }}</td>
            <th>Employee ID</th>
            <td>{{ $record->employee->employee_code }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $record->employee->department->name ?? 'N/A' }}</td>
            <th>Designation</th>
            <td>{{ $record->employee->designation->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date of Joining</th>
            <td>{{ $record->employee->joining_date ? \Carbon\Carbon::parse($record->employee->joining_date)->format('d M Y') : 'N/A' }}</td>
            <th>PAN Number</th>
            <td>{{ $record->employee->pan_number ?? 'N/A' }}</td>
        </tr>
    </table>

    @php
        $bonuses = \App\Models\Bonus::where('employee_id', $record->employee_id)
            ->whereYear('payout_date', \Carbon\Carbon::parse($record->payrollCycle->start_date)->year)
            ->whereMonth('payout_date', \Carbon\Carbon::parse($record->payrollCycle->start_date)->month)
            ->get();
            
        $extraDeductionsList = \App\Models\Deduction::where('employee_id', $record->employee_id)
            ->whereYear('month', \Carbon\Carbon::parse($record->payrollCycle->start_date)->year)
            ->whereMonth('month', \Carbon\Carbon::parse($record->payrollCycle->start_date)->month)
            ->get();
            
        $startDate = \Carbon\Carbon::parse($record->payrollCycle->start_date);
        $endDate = \Carbon\Carbon::parse($record->payrollCycle->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        
        $presentDays = \App\Models\Attendance::where('employee_id', $record->employee_id)
            ->whereBetween('date', [$record->payrollCycle->start_date, $record->payrollCycle->end_date])
            ->whereIn('status', ['present', 'Present'])
            ->count();
            
        $halfDays = \App\Models\Attendance::where('employee_id', $record->employee_id)
            ->whereBetween('date', [$record->payrollCycle->start_date, $record->payrollCycle->end_date])
            ->whereIn('status', ['half_day', 'Half Day'])
            ->count();
            
        // Use the exact values from the processed record
        $basic = $record->basic_pay;
        $hra = $record->hra;
        $specialAllowance = $record->special_allowance;
        $pf = $record->pf;
        $esi = $record->esi;
        $lossOfPay = $record->loss_of_pay;
        $otherDeductions = $record->extra_deductions;
        $incentives = $record->incentives;
        $reimbursement = $record->reimbursement;
        $pt = $record->professional_taxes;
        $tds = $record->tds_records;
        
        $baseGross = $record->gross_pay - $record->bonuses - $incentives - $reimbursement + $lossOfPay;
        $lopDays = ($baseGross > 0) ? round(($lossOfPay * $totalDays) / $baseGross, 1) : 0;
        $effectivePresentDays = $totalDays - $lopDays;
        $absentDays = $lopDays;
    @endphp

    <div class="section-title">Attendance Details</div>
    <table>
        <tr>
            <th>Total Days in Month</th>
            <td>{{ $totalDays }}</td>
            <th>Effective Present Days</th>
            <td>{{ round($effectivePresentDays) }}</td>
        </tr>
        <tr>
            <th>Absent/Leave Days</th>
            <td>{{ round($absentDays) }}</td>
            <th>Loss of Pay (LOP)</th>
            <td style="color: red;">{{ number_format($lossOfPay, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Salary Details</div>
    <table>
        <thead>
            <tr>
                <th>Earnings</th>
                <th class="text-right">Amount</th>
                <th>Deductions</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td class="text-right">{{ number_format($basic, 2) }}</td>
                <td>Provident Fund (PF)</td>
                <td class="text-right">{{ number_format($pf, 2) }}</td>
            </tr>
            <tr>
                <td>House Rent Allowance (HRA)</td>
                <td class="text-right">{{ number_format($hra, 2) }}</td>
                <td>Employee State Insurance (ESI)</td>
                <td class="text-right">{{ number_format($esi, 2) }}</td>
            </tr>
            <tr>
                <td>Special Allowance</td>
                <td class="text-right">{{ number_format($specialAllowance, 2) }}</td>
                <td>Professional Tax (PT)</td>
                <td class="text-right">{{ number_format($pt, 2) }}</td>
            </tr>
            <tr>
                <td>Incentives</td>
                <td class="text-right">{{ number_format($incentives, 2) }}</td>
                <td>Tax Deducted at Source (TDS)</td>
                <td class="text-right">{{ number_format($tds, 2) }}</td>
            </tr>
            <tr>
                <td>Reimbursements</td>
                <td class="text-right">{{ number_format($reimbursement, 2) }}</td>
                <td>Tax / Other Deductions</td>
                <td class="text-right">{{ number_format($otherDeductions, 2) }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right"></td>
                <td>Loss of Pay (Absence)</td>
                <td class="text-right">{{ number_format($lossOfPay, 2) }}</td>
            </tr>
            
            @foreach($bonuses as $bonus)
            <tr>
                <td>Bonus: {{ $bonus->bonus_type }}</td>
                <td class="text-right">{{ number_format($bonus->amount, 2) }}</td>
                <td></td>
                <td class="text-right"></td>
            </tr>
            @endforeach

            @foreach($extraDeductionsList as $deduction)
            <tr>
                <td></td>
                <td class="text-right"></td>
                <td>Deduction: {{ $deduction->deduction_type }}</td>
                <td class="text-right">{{ number_format($deduction->amount, 2) }}</td>
            </tr>
            @endforeach
            

            <tr>
                <td>&nbsp;</td>
                <td class="text-right"></td>
                <td>&nbsp;</td>
                <td class="text-right"></td>
            </tr>
        </tbody>
        <tfoot class="totals">
            <tr>
                <th>Total Earnings</th>
                <th class="text-right">{{ number_format($record->gross_pay, 2) }}</th>
                <th>Total Deductions</th>
                <th class="text-right">{{ number_format($record->total_deductions, 2) }}</th>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Net Pay</th>
                <th class="text-right">{{ number_format($record->net_pay, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 50px;">
        <p><em>This is a computer generated document and does not require a signature.</em></p>
    </div>
</body>
</html>
