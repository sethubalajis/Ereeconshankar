<?php

namespace App\Filament\Branch\Resources\KpiReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class KpiReportForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateKpiValue = function ($get, $set) {
            $kpiName = $get('kpi_name');
            $fromDate = $get('from_date');
            $toDate = $get('to_date');
            $date = $get('report_date');

            if ($kpiName && ($fromDate || $toDate || $date)) {
                $calcValue = 0.0;
                $empCount = max(1, \App\Models\Employee::count());

                switch ($kpiName) {
                    case 'Revenue Per Employee':
                        $query = \App\Models\PosSale::query();
                        if ($fromDate && $toDate) {
                            $query->whereBetween('sale_date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $query->whereDate('sale_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $query->whereDate('sale_date', '<=', $toDate);
                        } elseif ($date) {
                            $query->whereDate('sale_date', $date);
                        }
                        $totalRevenue = (float) $query->sum('net_amount');
                        $calcValue = round($totalRevenue / $empCount, 2);
                        break;

                    case 'Cost Per Employee':
                        $query = \App\Models\PurchaseOrder::query();
                        if ($fromDate && $toDate) {
                            $query->whereBetween('po_date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $query->whereDate('po_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $query->whereDate('po_date', '<=', $toDate);
                        } elseif ($date) {
                            $query->whereDate('po_date', $date);
                        }
                        $totalCost = (float) $query->sum('total_amount');
                        $calcValue = round($totalCost / $empCount, 2);
                        break;

                    case 'Employee Attendance Rate':
                        $query = \App\Models\Attendance::query();
                        if ($fromDate && $toDate) {
                            $query->whereBetween('date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $query->whereDate('date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $query->whereDate('date', '<=', $toDate);
                        } elseif ($date) {
                            $query->whereDate('date', $date);
                        }
                        $total = $query->count();
                        $present = (clone $query)->where('status', 'present')->count();
                        $calcValue = $total > 0 ? round(($present / $total) * 100, 2) : 100.0;
                        break;

                    case 'Employee Retention Rate':
                        $exitsQuery = \App\Models\EmployeeExit::query();
                        if ($fromDate && $toDate) {
                            $exitsQuery->whereBetween('resignation_date', [$fromDate, $toDate]);
                        }
                        $exitsCount = $exitsQuery->count();
                        $calcValue = round(max(0, (($empCount - $exitsCount) / $empCount) * 100), 2);
                        break;

                    default:
                        $calcValue = (float) ($get('value') ?? 0.0);
                        break;
                }

                $set('value', (float) $calcValue);
            }
        };

        return $schema
            ->components([
                Select::make('kpi_name')
                    ->label('Report / KPI Name')
                    ->options([
                        'Revenue Per Employee' => 'Revenue Per Employee',
                        'Cost Per Employee' => 'Cost Per Employee',
                        'Employee Attendance Rate' => 'Employee Attendance Rate',
                        'Employee Retention Rate' => 'Employee Retention Rate',
                        'Sales Target Achievement' => 'Sales Target Achievement',
                        'Customer Satisfaction Score' => 'Customer Satisfaction Score',
                        'Net Profit Margin' => 'Net Profit Margin',
                        'Inventory Turnover Ratio' => 'Inventory Turnover Ratio',
                        'Order Fulfillment Time' => 'Order Fulfillment Time',
                    ])
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateKpiValue),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateKpiValue),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateKpiValue),
                DatePicker::make('report_date')
                    ->label('Report Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live()
                    ->afterStateUpdated($updateKpiValue),
                TextInput::make('value')
                    ->label('KPI Metric Value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $kpiName = $get('kpi_name');
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $date = $get('report_date');
                        
                        if (!$kpiName || (!$fromDate && !$toDate && !$date)) {
                            return new HtmlString('<p class="text-gray-500">Please select a KPI report name and date range to view details.</p>');
                        }

                        $empCount = \App\Models\Employee::count();
                        $salesQuery = \App\Models\PosSale::query();
                        if ($fromDate && $toDate) {
                            $salesQuery->whereBetween('sale_date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $salesQuery->whereDate('sale_date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $salesQuery->whereDate('sale_date', '<=', $toDate);
                        } elseif ($date) {
                            $salesQuery->whereDate('sale_date', $date);
                        }
                        $sales = $salesQuery->get();
                        $totalSales = $sales->sum('net_amount');

                        $attendanceQuery = \App\Models\Attendance::query();
                        if ($fromDate && $toDate) {
                            $attendanceQuery->whereBetween('date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $attendanceQuery->whereDate('date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $attendanceQuery->whereDate('date', '<=', $toDate);
                        }
                        $totalAttendance = $attendanceQuery->count();
                        $presentCount = (clone $attendanceQuery)->where('status', 'present')->count();

                        $html = '<div class="space-y-6">';

                        // Summary Statistics Cards
                        $html .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">';
                        $html .= '<div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-center"><div class="text-xs font-semibold text-blue-600 uppercase">Selected KPI</div><div class="text-md font-bold text-blue-900">' . htmlspecialchars($kpiName) . '</div></div>';
                        $html .= '<div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-center"><div class="text-xs font-semibold text-emerald-600 uppercase">Active Employees</div><div class="text-xl font-bold text-emerald-900">' . $empCount . '</div></div>';
                        $html .= '<div class="p-3 bg-indigo-50 border border-indigo-200 rounded-lg text-center"><div class="text-xs font-semibold text-indigo-600 uppercase">Total Sales Revenue</div><div class="text-xl font-bold text-indigo-900">₹' . number_format($totalSales, 2) . '</div></div>';
                        $html .= '<div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-center"><div class="text-xs font-semibold text-amber-600 uppercase">Attendance Logs</div><div class="text-xl font-bold text-amber-900">' . $totalAttendance . '</div></div>';
                        $html .= '</div>';

                        $html .= '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse border">';
                        $html .= '<thead><tr class="bg-gray-100"><th class="p-2 border">KPI Metric</th><th class="p-2 border">Period</th><th class="p-2 border">Calculated Value</th></tr></thead><tbody>';
                        $periodStr = ($fromDate && $toDate) ? ($fromDate . ' to ' . $toDate) : ($fromDate ?? $toDate ?? $date);
                        $html .= '<tr>';
                        $html .= '<td class="p-2 border font-medium">' . htmlspecialchars($kpiName) . '</td>';
                        $html .= '<td class="p-2 border">' . $periodStr . '</td>';
                        $html .= '<td class="p-2 border font-bold text-blue-700">' . number_format((float)($get('value') ?? 0), 2) . '</td>';
                        $html .= '</tr>';
                        $html .= '</tbody></table></div>';

                        $html .= '</div>';
                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
