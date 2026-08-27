<?php

namespace App\Filament\Branch\Resources\HrReports\Schemas;

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
                        return view('filament.reports.hr-report-placeholder', [
                            'branchId' => $get('branch_id'),
                            'fromDate' => $get('from_date'),
                            'toDate' => $get('to_date'),
                            'reportMonth' => $get('report_month'),
                        ]);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
