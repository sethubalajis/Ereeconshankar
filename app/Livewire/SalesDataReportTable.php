<?php

namespace App\Livewire;

use App\Models\PosSaleItem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use App\Services\DateTimeFormatter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;
use Filament\Actions\Exports\ExportColumn;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use App\Traits\ExportsReportData;

class SalesDataReportTable extends Component implements HasForms, HasTable, HasActions
{
    use ExportsReportData;
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public $branchId;
    public $fromDate;
    public $toDate;
    public $reportDate;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PosSaleItem::query()
                    ->with(['posSale', 'posSale.customer', 'product', 'productVariant', 'branch'])
                    ->whereHas('posSale', function (Builder $query) {
                        if ($this->branchId) {
                            $query->where('branch_id', $this->branchId);
                        } else {
                            // Enforce branch access security if not filtering by specific branch
                            $userBranchId = session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);
                            if ($userBranchId) {
                                $query->where('branch_id', $userBranchId);
                            }
                        }
                        if ($this->fromDate && $this->toDate) {
                            $query->whereBetween('sale_date', [$this->fromDate, $this->toDate]);
                        } elseif ($this->fromDate) {
                            $query->whereDate('sale_date', '>=', $this->fromDate);
                        } elseif ($this->toDate) {
                            $query->whereDate('sale_date', '<=', $this->toDate);
                        } elseif ($this->reportDate) {
                            $query->whereDate('sale_date', $this->reportDate);
                        }
                    })
            )
            ->columns([
                TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                TextColumn::make('posSale.invoice_number')
                    ->label('Invoice No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('posSale.sale_date')
                    ->label('Date')
                    ->formatStateUsing(fn ($state) => DateTimeFormatter::formatDate($state))
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->hidden(fn () => (bool) $this->branchId) // Hide if specific branch selected
                    ->sortable(),
                TextColumn::make('posSale.customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('productVariant.name')
                    ->label('Variant')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->label('TOTAL')),
                TextColumn::make('gross_amount')
                    ->label('Subtotal')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('gst_amount')
                    ->label('GST')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('net_amount')
                    ->label('Total')
                    ->money('inr')
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(fn () => null) // handled via Alpine or JS ideally, but let's just make it call JS
                    ->extraAttributes(['onclick' => 'window.print()']),
            ])
            ->emptyStateHeading('No records found for the selected branch and date range.')
            ->emptyStateDescription('')
            ->deferLoading()
            ->striped();
    }

    public function getSummaryStatsProperty()
    {
        $query = \App\Models\PosSale::query();

        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        } else {
            $userBranchId = session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);
            if ($userBranchId) {
                $query->where('branch_id', $userBranchId);
            }
        }
        
        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('sale_date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $query->whereDate('sale_date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->whereDate('sale_date', '<=', $this->toDate);
        } elseif ($this->reportDate) {
            $query->whereDate('sale_date', $this->reportDate);
        }

        $branchName = 'All Branches';
        if ($this->branchId) {
            $branch = \App\Models\Branch::find($this->branchId);
            $branchName = $branch ? $branch->name : 'All Branches';
        }

        return [
            'branch' => $branchName,
            'period' => ($this->fromDate ?: 'Start') . ' - ' . ($this->toDate ?: 'End'),
            'total_invoices' => $query->count(),
            'total_items_sold' => $query->withSum('posSaleItems as total_qty', 'quantity')->get()->sum('total_qty'),
            'gross_sales' => $query->sum('total_amount'),
            'total_discount' => $query->sum('discount_amount'),
            'total_gst' => $query->sum('tax_amount'),
            'net_sales' => $query->sum('net_amount'),
        ];
    }

    public function render()
    {
        return view('livewire.sales-data-report-table', [
            'summary' => $this->summaryStats
        ]);
    }
}
