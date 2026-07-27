<?php

namespace App\Filament\Resources\FinancialReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class FinancialReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('report_type')
                    ->options([
                        'Profit & Loss' => 'Profit & Loss',
                        'Balance Sheet' => 'Balance Sheet',
                        'Cash Flow' => 'Cash Flow',
                        'Trial Balance' => 'Trial Balance',
                    ])
                    ->required()
                    ->live(),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('as_of_date')
                    ->label('As Of Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live(),
                Textarea::make('data')
                    ->columnSpanFull(),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $date = $get('as_of_date');
                        
                        // Handle branch_id safely
                        $branchId = null;
                        try {
                            $branchId = $get('branch_id');
                        } catch (\Exception $e) {
                            $branchId = null;
                        }
                        
                        if (!$fromDate && !$toDate && !$date) return new HtmlString('<p class="text-gray-500">Please select a date range or as of date to view details.</p>');
                        
                        $query = \App\Models\JournalEntry::whereHas('journal', function ($journalQuery) use ($fromDate, $toDate, $date) {
                            if ($fromDate && $toDate) {
                                $journalQuery->whereBetween('journal_date', [$fromDate, $toDate]);
                            } elseif ($fromDate) {
                                $journalQuery->whereDate('journal_date', '>=', $fromDate);
                            } elseif ($toDate) {
                                $journalQuery->whereDate('journal_date', '<=', $toDate);
                            } elseif ($date) {
                                if (strlen($date) === 7) {
                                    $journalQuery->whereYear('journal_date', substr($date, 0, 4))
                                        ->whereMonth('journal_date', substr($date, 5, 2));
                                } else {
                                    $journalQuery->whereDate('journal_date', $date);
                                }
                            }
                        });
                        
                        if ($branchId) {
                            // Check if branch_id exists on the model
                            if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\JournalEntry)->getTable(), 'branch_id')) {
                                $query->where('branch_id', $branchId);
                            }
                        }
                        
                        $records = $query->take(50)->get();
                        
                        if ($records->isEmpty()) return new HtmlString('<p class="text-gray-500">No records found for this date.</p>');
                        
                        $html = '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">';
                        $html .= '<thead><tr class="bg-gray-100">';
                        $html .= '<th class="p-2 border">Entry Number</th>';
$html .= '<th class="p-2 border">Description</th>';
$html .= '<th class="p-2 border">Amount</th>';
$html .= '</tr></thead><tbody>';
foreach ($records as $record) {
$html .= '<tr>';
$val = $record->entry_number;
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->description;
$html .= '<td class="p-2 border">' . $val . '</td>';
$html .= '<td class="p-2 border">₹' . number_format((float) $record->total_credit, 2) . '</td>';
$html .= '</tr>';
}
$html .= '</tbody></table></div>';
return new HtmlString($html);
})
                    ->columnSpanFull(),
            ]);
    }
}
