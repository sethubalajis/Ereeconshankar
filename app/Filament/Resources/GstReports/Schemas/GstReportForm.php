<?php

namespace App\Filament\Resources\GstReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class GstReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('report_type')
                    ->required()
                    ->live(),
                DatePicker::make('period_start')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('period_end')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                Textarea::make('summary_data')
                    ->columnSpanFull(),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $date = $get('period_start');
                        
                        // Handle branch_id safely
                        $branchId = null;
                        try {
                            $branchId = $get('branch_id');
                        } catch (\Exception $e) {
                            $branchId = null;
                        }
                        
                        if (!$date) return new HtmlString('<p class="text-gray-500">Please select a date to view details.</p>');
                        
                        // Parse date properly if needed (sometimes it comes as Y-m)
                        if (strlen($date) === 7) { // Y-m
                            $query = \App\Models\PosSale::whereYear('sale_date', substr($date, 0, 4))
                                ->whereMonth('sale_date', substr($date, 5, 2));
                        } else {
                            $query = \App\Models\PosSale::whereDate('sale_date', $date);
                        }
                        
                        if ($branchId) {
                            // Check if branch_id exists on the model
                            if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\PosSale)->getTable(), 'branch_id')) {
                                $query->where('branch_id', $branchId);
                            }
                        }
                        
                        $records = $query->take(50)->get();
                        
                        if ($records->isEmpty()) return new HtmlString('<p class="text-gray-500">No records found for this date.</p>');
                        
                        $html = '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">';
                        $html .= '<thead><tr class="bg-gray-100">';
                        $html .= '<th class="p-2 border">Invoice</th>';
$html .= '<th class="p-2 border">Tax Amount</th>';
$html .= '<th class="p-2 border">Total Amount</th>';
$html .= '</tr></thead><tbody>';
foreach ($records as $record) {
$html .= '<tr>';
$val = $record->invoice_number;
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->tax_amount;
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->total_amount;
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
