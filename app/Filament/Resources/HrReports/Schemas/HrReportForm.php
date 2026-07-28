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
        return $schema
            ->components([
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('report_month')
                    ->label('Report Month / Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live(),
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
                        
                        if (!$fromDate && !$toDate && !$date) return new HtmlString('<p class="text-gray-500">Please select a date range to view details.</p>');
                        
                        $query = \App\Models\Attendance::query();

                        if ($fromDate && $toDate) {
                            $query->whereBetween('date', [$fromDate, $toDate]);
                        } elseif ($fromDate) {
                            $query->whereDate('date', '>=', $fromDate);
                        } elseif ($toDate) {
                            $query->whereDate('date', '<=', $toDate);
                        } elseif ($date) {
                            if (strlen($date) === 7) {
                                $query->whereYear('date', substr($date, 0, 4))
                                    ->whereMonth('date', substr($date, 5, 2));
                            } else {
                                $query->whereDate('date', $date);
                            }
                        }
                        
                        if ($branchId) {
                            // Check if branch_id exists on the model
                            if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Attendance)->getTable(), 'branch_id')) {
                                $query->where('branch_id', $branchId);
                            }
                        }
                        
                        $records = $query->take(50)->get();
                        
                        if ($records->isEmpty()) return new HtmlString('<p class="text-gray-500">No records found for this date.</p>');
                        
                        $html = '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">';
                        $html .= '<thead><tr class="bg-gray-100">';
                        $html .= '<th class="p-2 border">Employee</th>';
$html .= '<th class="p-2 border">Status</th>';
$html .= '<th class="p-2 border">Check In</th>';
$html .= '<th class="p-2 border">Check Out</th>';
$html .= '</tr></thead><tbody>';
foreach ($records as $record) {
$html .= '<tr>';
$val = $record->employee ? $record->employee->name : 'N/A';
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->status;
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->check_in_time;
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->check_out_time;
$html .= '<td class="p-2 border">' . $val . '</td>';
$html .= '</tr>';
}
$html .= '</tbody></table></div>';
return new HtmlString($html);
})
                    ->columnSpanFull(),
            ]);
    }
}
