<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HR Report Detailed Breakdown</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 13px;
            color: #475569;
            margin-top: 3px;
            font-weight: 600;
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 8px;
            font-size: 11px;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
        }
        .stat-title {
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }
        .stat-val {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 18px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .data-table th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #1e293b;
        }
        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 10.5px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e2e8f0;
            color: #334155;
        }
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">eReckon ERP System</div>
        <div class="subtitle">Comprehensive HR Report</div>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>
                    @if($record->from_date && $record->to_date)
                        <strong>Period:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($record->to_date)->format('d M Y') }}
                    @elseif($record->from_date)
                        <strong>From Date:</strong> {{ \Carbon\Carbon::parse($record->from_date)->format('d M Y') }}
                    @elseif($record->report_month)
                        <strong>Report Date / Month:</strong> {{ \Carbon\Carbon::parse($record->report_month)->format('d M Y') }}
                    @endif
                </td>
                <td class="text-right">
                    <strong>Report ID:</strong> #{{ $record->id ?? time() }}
                </td>
            </tr>
        </table>
        
        <table style="width: 100%; margin-top: 10px; border-collapse: separate; border-spacing: 6px;">
            <tr>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #2563eb;">
                        <div class="stat-title">Total Active Employees</div>
                        <div class="stat-val">{{ $record->total_employees ?? 0 }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #16a34a;">
                        <div class="stat-title">New Hires</div>
                        <div class="stat-val">{{ $newHires->count() }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #dc2626;">
                        <div class="stat-title">Exits</div>
                        <div class="stat-val">{{ $exits->count() }}</div>
                    </div>
                </td>
                <td style="width: 25%;">
                    <div class="stat-card" style="border-left: 3px solid #d97706;">
                        <div class="stat-title">Leaves</div>
                        <div class="stat-val">{{ $leaves->count() }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- 1. New Hires --}}
    @if($newHires->isNotEmpty())
        <div class="section-title">New Hires ({{ $newHires->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>Code</th>
                    <th>Employee Name</th>
                    <th>Joining Date</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($newHires as $index => $emp)
                    @php
                        $name = trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? ''));
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $emp->employee_code ?? 'N/A' }}</strong></td>
                        <td>{{ $name ?: 'N/A' }}</td>
                        <td>{{ $emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('d M Y') : 'N/A' }}</td>
                        <td>{{ $emp->department->name ?? 'N/A' }}</td>
                        <td>{{ $emp->designation->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            <span class="badge badge-success">{{ ucfirst($emp->status ?? 'Active') }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- 2. Employee Exits --}}
    @if($exits->isNotEmpty())
        <div class="section-title">Employee Exits ({{ $exits->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>Employee</th>
                    <th>Resignation Date</th>
                    <th>Last Working Day</th>
                    <th>Exit Type</th>
                    <th>Reason</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exits as $index => $exit)
                    @php
                        $emp = $exit->employee;
                        $empName = $emp ? trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) : 'N/A';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $empName }}</strong> ({{ $emp->employee_code ?? 'N/A' }})</td>
                        <td>{{ $exit->resignation_date ? \Carbon\Carbon::parse($exit->resignation_date)->format('d M Y') : 'N/A' }}</td>
                        <td>{{ $exit->last_working_day ? \Carbon\Carbon::parse($exit->last_working_day)->format('d M Y') : 'N/A' }}</td>
                        <td>{{ $exit->exit_type ?? 'N/A' }}</td>
                        <td>{{ $exit->reason ?? 'N/A' }}</td>
                        <td class="text-center">
                            <span class="badge badge-danger">{{ ucfirst($exit->status ?? 'N/A') }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- 3. Employee Leaves --}}
    @if($leaves->isNotEmpty())
        <div class="section-title">Leave Details ({{ $leaves->count() }})</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $index => $leave)
                    @php
                        $emp = $leave->employee;
                        $empName = $emp ? trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) : 'N/A';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $empName }}</strong> ({{ $emp->employee_code ?? 'N/A' }})</td>
                        <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                        <td>{{ $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('d M Y') : 'N/A' }}</td>
                        <td>{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="text-center">
                            <span class="badge badge-warning">{{ ucfirst($leave->status ?? 'N/A') }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- 4. Attendance Details --}}
    <div class="section-title">Attendance Logs ({{ $attendanceRecords->count() }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Date</th>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th class="text-center">Status</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendanceRecords as $index => $record)
                @php
                    $emp = $record->employee;
                    $empName = $emp ? trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) : 'N/A';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $record->date ? \Carbon\Carbon::parse($record->date)->format('d M Y') : 'N/A' }}</td>
                    <td><strong>{{ $emp->employee_code ?? 'N/A' }}</strong></td>
                    <td>{{ $empName }}</td>
                    <td class="text-center">
                        <span class="badge {{ strtolower($record->status) == 'present' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($record->status ?? 'N/A') }}
                        </span>
                    </td>
                    <td>{{ $record->check_in_time ?? 'N/A' }}</td>
                    <td>{{ $record->check_out_time ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b; padding: 15px;">
                        No attendance logs recorded for the selected date range.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &bull; eReckon ERP Confidential HR Report
    </div>
</body>
</html>
