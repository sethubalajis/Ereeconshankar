<?php

namespace App\Livewire;

use App\Models\PosSale;
use App\Models\PurchaseOrder;
use App\Traits\ExportsReportData;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GstDataReportTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public $branchId;
    public $reportType;
    public $periodStart;
    public $periodEnd;

    public function table(Table $table): Table
    {
        $type = strtoupper($this->reportType ?? 'GSTR-1');
        $branchId = $this->branchId ?? session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);

        $sales = PosSale::query()
            ->select([
                'pos_sales.id',
                'pos_sales.invoice_number as doc_no',
                'pos_sales.sale_date as date',
                'pos_sales.branch_id',
                DB::raw("'Sales' as type"),
                DB::raw('COALESCE(customers.name, "Walk-in Customer") as party_name'),
                DB::raw('(pos_sales.total_amount - pos_sales.tax_amount) as taxable_value'),
                DB::raw('(pos_sales.tax_amount / 2) as cgst'),
                DB::raw('(pos_sales.tax_amount / 2) as sgst'),
                DB::raw('0 as igst'),
                'pos_sales.tax_amount',
                'pos_sales.total_amount'
            ])
            ->leftJoin('customers', 'customers.id', '=', 'pos_sales.customer_id');

        if ($this->periodStart) {
            $sales->whereDate('pos_sales.sale_date', '>=', $this->periodStart);
        }
        if ($this->periodEnd) {
            $sales->whereDate('pos_sales.sale_date', '<=', $this->periodEnd);
        }
        if ($branchId) {
            $sales->where('pos_sales.branch_id', $branchId);
        }

        $purchases = PurchaseOrder::query()
            ->select([
                'purchase_orders.id',
                DB::raw('COALESCE(purchase_orders.po_number, CONCAT("PO-", purchase_orders.id)) as doc_no'),
                DB::raw('COALESCE(purchase_orders.po_date, DATE(purchase_orders.created_at)) as date'),
                'purchase_orders.branch_id',
                DB::raw("'Purchase' as type"),
                DB::raw('COALESCE(vendors.name, "Vendor") as party_name'),
                DB::raw('(purchase_orders.total_amount - COALESCE(purchase_orders.tax_amount, (purchase_orders.total_amount * 0.18))) as taxable_value'),
                DB::raw('(COALESCE(purchase_orders.tax_amount, (purchase_orders.total_amount * 0.18)) / 2) as cgst'),
                DB::raw('(COALESCE(purchase_orders.tax_amount, (purchase_orders.total_amount * 0.18)) / 2) as sgst'),
                DB::raw('0 as igst'),
                DB::raw('COALESCE(purchase_orders.tax_amount, (purchase_orders.total_amount * 0.18)) as tax_amount'),
                'purchase_orders.total_amount'
            ])
            ->leftJoin('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id');

        if ($this->periodStart) {
            $purchases->whereDate(DB::raw('COALESCE(purchase_orders.po_date, DATE(purchase_orders.created_at))'), '>=', $this->periodStart);
        }
        if ($this->periodEnd) {
            $purchases->whereDate(DB::raw('COALESCE(purchase_orders.po_date, DATE(purchase_orders.created_at))'), '<=', $this->periodEnd);
        }
        if ($branchId) {
            $purchases->where('purchase_orders.branch_id', $branchId);
        }

        // Determine which queries to include based on GSTR type
        $includeSales = in_array($type, ['GSTR-1', 'GSTR-3B', 'GSTR-9', 'GSTR-9C']) || empty($type);
        $includePurchases = in_array($type, ['GSTR-2A', 'GSTR-2B', 'GSTR-3B', 'GSTR-4', 'GSTR-9', 'GSTR-9C']);

        $baseQuery = null;
        if ($includeSales && $includePurchases) {
            $baseQuery = $sales->unionAll($purchases);
        } elseif ($includeSales) {
            $baseQuery = $sales;
        } elseif ($includePurchases) {
            $baseQuery = $purchases;
        } else {
            $baseQuery = $sales->where('pos_sales.id', -1); // Empty
        }

        // We wrap the union in a subquery and map it to PosSale model so Filament Table can use it
        $query = PosSale::query()->fromSub($baseQuery, 'gst_reports')->with('branch');

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                TextColumn::make('doc_no')
                    ->label('Invoice No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->hidden(fn () => (bool) $this->branchId),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sales' => 'info',
                        'Purchase' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('party_name')
                    ->label('Customer/Vendor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('taxable_value')
                    ->label('Taxable Value')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('TOTAL')),
                TextColumn::make('cgst')
                    ->label('CGST')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('sgst')
                    ->label('SGST')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('igst')
                    ->label('IGST')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('tax_amount')
                    ->label('Total GST')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('total_amount')
                    ->label('Invoice Total')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(fn () => null)
                    ->extraAttributes(['onclick' => 'window.print()']),
            ])
            ->emptyStateHeading('No GST records found')
            ->emptyStateDescription('No records found for the selected report type and period.')
            ->deferLoading()
            ->striped();
    }

    public function getSummaryStatsProperty()
    {
        // Simple summary without evaluating the whole union query again
        return [
            'report_type' => $this->reportType ?? 'GSTR-1',
            'period' => ($this->periodStart ?: 'Start') . ' - ' . ($this->periodEnd ?: 'End'),
        ];
    }

    public function render()
    {
        return view('livewire.gst-data-report-table', [
            'summary' => $this->summaryStats
        ]);
    }
}
