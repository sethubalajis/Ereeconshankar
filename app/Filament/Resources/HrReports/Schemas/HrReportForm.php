<?php

namespace App\Filament\Resources\HrReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class HrReportForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateHrTotals = function ($get, $set) {
            $fromDate = $get('from_date');
            $toDate = $get('to_date');
            $date = $get('report_month');

            $branchId = null;
            try {
                $branchId = $get('branch_id');
            } catch (\Exception $e) {
                $branchId = null;
            }

            if ($fromDate || $toDate || $date) {
                // 1. Calculate Total Employees (active employees as of to_date / selected period)
                $totalEmpQuery = \App\Models\Employee::query();
                if ($branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Employee)->getTable(), 'branch_id')) {
                    $totalEmpQuery->where('branch_id', $branchId);
                }
                if ($toDate) {
                    $totalEmpQuery->where(function ($q) use ($toDate) {
                        $q->whereNull('joining_date')->orWhereDate('joining_date', '<=', $toDate);
                    });
                } elseif ($date) {
                    $totalEmpQuery->where(function ($q) use ($date) {
                        $q->whereNull('joining_date')->orWhereDate('joining_date', '<=', $date);
                    });
                }
                $set('total_employees', $totalEmpQuery->count());

                // 2. Calculate New Hires (employees joined in the selected date range)
                $newHiresQuery = \App\Models\Employee::query();
                if ($branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Employee)->getTable(), 'branch_id')) {
                    $newHiresQuery->where('branch_id', $branchId);
                }
                if ($fromDate && $toDate) {
                    $newHiresQuery->whereBetween('joining_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $newHiresQuery->whereDate('joining_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $newHiresQuery->whereDate('joining_date', '<=', $toDate);
                } elseif ($date) {
                    if (strlen($date) === 7) {
                        $newHiresQuery->whereYear('joining_date', substr($date, 0, 4))
                            ->whereMonth('joining_date', substr($date, 5, 2));
                    } else {
                        $newHiresQuery->whereDate('joining_date', $date);
                    }
                }
                $set('new_hires', $newHiresQuery->count());

                // 3. Calculate Exits (employees exited in the selected date range)
                $exitsQuery = \App\Models\EmployeeExit::query();
                if ($fromDate && $toDate) {
                    $exitsQuery->where(function ($q) use ($fromDate, $toDate) {
                        $q->whereBetween('resignation_date', [$fromDate, $toDate])
                          ->orWhereBetween('last_working_day', [$fromDate, $toDate]);
                    });
                } elseif ($fromDate) {
                    $exitsQuery->where(function ($q) use ($fromDate) {
                        $q->whereDate('resignation_date', '>=', $fromDate)
                          ->orWhereDate('last_working_day', '>=', $fromDate);
                    });
                } elseif ($toDate) {
                    $exitsQuery->where(function ($q) use ($toDate) {
                        $q->whereDate('resignation_date', '<=', $toDate)
                          ->orWhereDate('last_working_day', '<=', $toDate);
                    });
                } elseif ($date) {
                    if (strlen($date) === 7) {
                        $exitsQuery->where(function ($q) use ($date) {
                            $q->whereYear('resignation_date', substr($date, 0, 4))
                              ->whereMonth('resignation_date', substr($date, 5, 2))
                              ->orWhere(function ($q2) use ($date) {
                                  $q2->whereYear('last_working_day', substr($date, 0, 4))
                                     ->whereMonth('last_working_day', substr($date, 5, 2));
                              });
                        });
                    } else {
                        $exitsQuery->where(function ($q) use ($date) {
                            $q->whereDate('resignation_date', $date)
                              ->orWhereDate('last_working_day', $date);
                        });
                    }
                }
                $set('exits', $exitsQuery->count());
            }
        };

        return $schema
            ->components([
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateHrTotals),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateHrTotals),
                DatePicker::make('report_month')
                    ->label('Report Month / Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live()
                    ->afterStateUpdated($updateHrTotals),
                TextInput::make('total_employees')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('new_hires')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('exits')
                    ->required()
                    ->numeric()
                    ->default(0),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $date = $get('report_month');
                        
                        // Handle branch_id safely
                        $branchId = null;
                        try {
                            $branchId = $get('branch_id');
                        } catch (\Exception $e) {
                            $branchId = null;
                        }
                        
                        if (!$fromDate && !$toDate && !$date) return new HtmlString('<p class="text-gray-500">Please select a date range to view report details.</p>');
                        
                        // 1. New Hires Query
                        $newHiresQuery = \App\Models\Employee::with(['department', 'designation']);
                        if ($branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Employee)->getTable(), 'branch_id')) {
                            $newHiresQuery->where('branch_id', $branchId);
                        }
                        if ($fromDate && $toDate) {
                            $newHiresQuery->whereBetween('joining_date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $newHiresQuery->whereDate('joining_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $newHiresQuery->whereDate('joining_date', '<=', $toDate);
                        } elseif ($date) {
                            if (strlen($date) === 7) {
                                $newHiresQuery->whereYear('joining_date', substr($date, 0, 4))
                                    ->whereMonth('joining_date', substr($date, 5, 2));
                            } else {
                                $newHiresQuery->whereDate('joining_date', $date);
                            }
                        }
                        $newHires = $newHiresQuery->get();

                        // 2. Exits Query
                        $exitsQuery = \App\Models\EmployeeExit::with(['employee']);
                        if ($fromDate && $toDate) {
                            $exitsQuery->where(function ($q) use ($fromDate, $toDate) {
                                $q->whereBetween('resignation_date', [$fromDate, $toDate])
                                  ->orWhereBetween('last_working_day', [$fromDate, $toDate]);
                            });
                        } elseif ($fromDate) {
                            $exitsQuery->where(function ($q) use ($fromDate) {
                                $q->whereDate('resignation_date', '>=', $fromDate)
                                  ->orWhereDate('last_working_day', '>=', $fromDate);
                            });
                        } elseif ($toDate) {
                            $exitsQuery->where(function ($q) use ($toDate) {
                                $q->whereDate('resignation_date', '<=', $toDate)
                                  ->orWhereDate('last_working_day', '<=', $toDate);
                            });
                        } elseif ($date) {
                            if (strlen($date) === 7) {
                                $exitsQuery->where(function ($q) use ($date) {
                                    $q->whereYear('resignation_date', substr($date, 0, 4))
                                      ->whereMonth('resignation_date', substr($date, 5, 2))
                                      ->orWhere(function ($q2) use ($date) {
                                          $q2->whereYear('last_working_day', substr($date, 0, 4))
                                             ->whereMonth('last_working_day', substr($date, 5, 2));
                                      });
                                });
                            } else {
                                $exitsQuery->where(function ($q) use ($date) {
                                    $q->whereDate('resignation_date', $date)
                                      ->orWhereDate('last_working_day', $date);
                                });
                            }
                        }
                        $exits = $exitsQuery->get();

                        // 3. Leaves Query
                        $leavesQuery = \App\Models\EmployeeLeave::with(['employee', 'leaveType']);
                        if ($fromDate && $toDate) {
                            $leavesQuery->where(function ($q) use ($fromDate, $toDate) {
                                $q->whereBetween('start_date', [$fromDate, $toDate])
                                  ->orWhereBetween('end_date', [$fromDate, $toDate]);
                            });
                        } elseif ($fromDate) {
                            $leavesQuery->whereDate('start_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $leavesQuery->whereDate('end_date', '<=', $toDate);
                        } elseif ($date) {
                            if (strlen($date) === 7) {
                                $leavesQuery->whereYear('start_date', substr($date, 0, 4))
                                    ->whereMonth('start_date', substr($date, 5, 2));
                            } else {
                                $leavesQuery->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
                            }
                        }
                        $leaves = $leavesQuery->get();

                        // 4. Attendance Query (All records for selected date range)
                        $attendanceQuery = \App\Models\Attendance::with(['employee']);
                        if ($fromDate && $toDate) {
                            $attendanceQuery->whereBetween('date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $attendanceQuery->whereDate('date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $attendanceQuery->whereDate('date', '<=', $toDate);
                        } elseif ($date) {
                            if (strlen($date) === 7) {
                                $attendanceQuery->whereYear('date', substr($date, 0, 4))
                                    ->whereMonth('date', substr($date, 5, 2));
                            } else {
                                $attendanceQuery->whereDate('date', $date);
                            }
                        }
                        if ($branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Attendance)->getTable(), 'branch_id')) {
                            $attendanceQuery->where('branch_id', $branchId);
                        }
                        $records = $attendanceQuery->orderBy('date', 'desc')->get();

                        $html = '<div class="space-y-6">';

                        // Summary Statistics Cards
                        $html .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">';
                        $html .= '<div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-center"><div class="text-xs font-semibold text-blue-600 uppercase">New Hires</div><div class="text-xl font-bold text-blue-900">' . $newHires->count() . '</div></div>';
                        $html .= '<div class="p-3 bg-red-50 border border-red-200 rounded-lg text-center"><div class="text-xs font-semibold text-red-600 uppercase">Exits</div><div class="text-xl font-bold text-red-900">' . $exits->count() . '</div></div>';
                        $html .= '<div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-center"><div class="text-xs font-semibold text-amber-600 uppercase">Leaves</div><div class="text-xl font-bold text-amber-900">' . $leaves->count() . '</div></div>';
                        $html .= '<div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-center"><div class="text-xs font-semibold text-emerald-600 uppercase">Attendance Logs</div><div class="text-xl font-bold text-emerald-900">' . $records->count() . '</div></div>';
                        $html .= '</div>';

                        // New Hires Table
                        if ($newHires->isNotEmpty()) {
                            $html .= '<div class="mb-6"><h4 class="text-md font-bold mb-2 text-gray-800">New Hires (' . $newHires->count() . ')</h4>';
                            $html .= '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse border">';
                            $html .= '<thead><tr class="bg-gray-100"><th class="p-2 border">Code</th><th class="p-2 border">Employee Name</th><th class="p-2 border">Joining Date</th><th class="p-2 border">Department</th><th class="p-2 border">Designation</th><th class="p-2 border">Status</th></tr></thead><tbody>';
                            foreach ($newHires as $emp) {
                                $name = trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? ''));
                                $dept = $emp->department ? $emp->department->name : 'N/A';
                                $desig = $emp->designation ? $emp->designation->name : 'N/A';
                                $html .= '<tr>';
                                $html .= '<td class="p-2 border font-medium">' . ($emp->employee_code ?? 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($name ?: 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('Y-m-d') : 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . $dept . '</td>';
                                $html .= '<td class="p-2 border">' . $desig . '</td>';
                                $html .= '<td class="p-2 border">' . ucfirst($emp->status ?? 'Active') . '</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody></table></div></div>';
                        }

                        // Employee Exits Table
                        if ($exits->isNotEmpty()) {
                            $html .= '<div class="mb-6"><h4 class="text-md font-bold mb-2 text-gray-800">Employee Exits (' . $exits->count() . ')</h4>';
                            $html .= '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse border">';
                            $html .= '<thead><tr class="bg-gray-100"><th class="p-2 border">Employee</th><th class="p-2 border">Resignation Date</th><th class="p-2 border">Last Working Day</th><th class="p-2 border">Exit Type</th><th class="p-2 border">Reason</th><th class="p-2 border">Status</th></tr></thead><tbody>';
                            foreach ($exits as $exit) {
                                $emp = $exit->employee;
                                $empName = $emp ? trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) : 'N/A';
                                $html .= '<tr>';
                                $html .= '<td class="p-2 border font-medium">' . $empName . ' (' . ($emp->employee_code ?? 'N/A') . ')</td>';
                                $html .= '<td class="p-2 border">' . ($exit->resignation_date ? \Carbon\Carbon::parse($exit->resignation_date)->format('Y-m-d') : 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($exit->last_working_day ? \Carbon\Carbon::parse($exit->last_working_day)->format('Y-m-d') : 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($exit->exit_type ?? 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($exit->reason ?? 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ucfirst($exit->status ?? 'N/A') . '</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody></table></div></div>';
                        }

                        // Employee Leaves Table
                        if ($leaves->isNotEmpty()) {
                            $html .= '<div class="mb-6"><h4 class="text-md font-bold mb-2 text-gray-800">Leaves (' . $leaves->count() . ')</h4>';
                            $html .= '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse border">';
                            $html .= '<thead><tr class="bg-gray-100"><th class="p-2 border">Employee</th><th class="p-2 border">Leave Type</th><th class="p-2 border">Start Date</th><th class="p-2 border">End Date</th><th class="p-2 border">Status</th></tr></thead><tbody>';
                            foreach ($leaves as $leave) {
                                $emp = $leave->employee;
                                $empName = $emp ? trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) : 'N/A';
                                $leaveType = $leave->leaveType ? $leave->leaveType->name : 'N/A';
                                $html .= '<tr>';
                                $html .= '<td class="p-2 border font-medium">' . $empName . ' (' . ($emp->employee_code ?? 'N/A') . ')</td>';
                                $html .= '<td class="p-2 border">' . $leaveType . '</td>';
                                $html .= '<td class="p-2 border">' . ($leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') : 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') : 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ucfirst($leave->status ?? 'N/A') . '</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody></table></div></div>';
                        }

                        // Attendance Records Table (All records)
                        $html .= '<div class="mb-6"><h4 class="text-md font-bold mb-2 text-gray-800">Attendance Details (' . $records->count() . ')</h4>';
                        if ($records->isEmpty()) {
                            $html .= '<p class="text-gray-500">No attendance records found for the selected date range.</p>';
                        } else {
                            $html .= '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse border">';
                            $html .= '<thead><tr class="bg-gray-100"><th class="p-2 border">Date</th><th class="p-2 border">Employee</th><th class="p-2 border">Status</th><th class="p-2 border">Check In</th><th class="p-2 border">Check Out</th></tr></thead><tbody>';
                            foreach ($records as $record) {
                                $emp = $record->employee;
                                $empName = $emp ? trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? '')) : 'N/A';
                                if ($emp && !empty($emp->employee_code)) {
                                    $empName .= ' (' . $emp->employee_code . ')';
                                }
                                $html .= '<tr>';
                                $html .= '<td class="p-2 border">' . ($record->date ? \Carbon\Carbon::parse($record->date)->format('Y-m-d') : 'N/A') . '</td>';
                                $html .= '<td class="p-2 border font-medium">' . $empName . '</td>';
                                $html .= '<td class="p-2 border">' . ucfirst($record->status ?? 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($record->check_in_time ?? 'N/A') . '</td>';
                                $html .= '<td class="p-2 border">' . ($record->check_out_time ?? 'N/A') . '</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody></table></div>';
                        }
                        $html .= '</div></div>';

                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
