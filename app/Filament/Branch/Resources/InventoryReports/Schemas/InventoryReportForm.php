<?php

namespace App\Filament\Branch\Resources\InventoryReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class InventoryReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live(),
                DatePicker::make('report_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                TextInput::make('total_inventory_value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            
                Placeholder::make('report_details')
                    ->label('Report Details')
                    ->content(function ($get) {
                        $date = $get('report_date');
                        
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
                            $query = \App\Models\InventoryCount::whereYear('created_at', substr($date, 0, 4))
                                ->whereMonth('created_at', substr($date, 5, 2));
                        } else {
                            $query = \App\Models\InventoryCount::whereDate('created_at', $date);
                        }
                        
                        if ($branchId) {
                            // Check if branch_id exists on the model
                            if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\InventoryCount)->getTable(), 'branch_id')) {
                                $query->where('branch_id', $branchId);
                            }
                        }
                        
                        $records = $query->take(50)->get();
                        
                        if ($records->isEmpty()) return new HtmlString('<p class="text-gray-500">No records found for this date.</p>');
                        
                        $html = '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">';
                        $html .= '<thead><tr class="bg-gray-100">';
                        $html .= '<th class="p-2 border">Count ID</th>';
$html .= '<th class="p-2 border">Warehouse</th>';
$html .= '<th class="p-2 border">Status</th>';
$html .= '</tr></thead><tbody>';
foreach ($records as $record) {
$html .= '<tr>';
$val = $record->id;
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->warehouse ? $record->warehouse->name : 'N/A';
$html .= '<td class="p-2 border">' . $val . '</td>';
$val = $record->status;
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
