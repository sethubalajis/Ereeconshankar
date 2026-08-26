<?php

namespace App\Livewire;

use App\Models\Stock;
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
use App\Traits\ExportsReportData;

class InventoryDataReportTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;
    use ExportsReportData;

    public $branchId;
    public $fromDate;
    public $toDate;
    public $reportDate;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Stock::query()
                    ->with(['product', 'productVariant', 'productVariant.color', 'productVariant.size', 'branch'])
                    ->when($this->branchId, function (Builder $query) {
                        $query->where('branch_id', $this->branchId);
                    }, function (Builder $query) {
                        $userBranchId = session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);
                        if ($userBranchId) {
                            $query->where('branch_id', $userBranchId);
                        }
                    })
                    // Add subqueries for In/Out if date range provided
                    ->when($this->fromDate || $this->toDate, function(Builder $query) {
                         $query->addSelect([
                             'stock_in' => \App\Models\StockLedger::select(DB::raw('SUM(inward_qty)'))
                                 ->whereColumn('product_id', 'stocks.product_id')
                                 ->whereColumn('branch_id', 'stocks.branch_id')
                                 ->when($this->fromDate, fn($q) => $q->whereDate('transaction_date', '>=', $this->fromDate))
                                 ->when($this->toDate, fn($q) => $q->whereDate('transaction_date', '<=', $this->toDate)),
                             'stock_out' => \App\Models\StockLedger::select(DB::raw('SUM(outward_qty)'))
                                 ->whereColumn('product_id', 'stocks.product_id')
                                 ->whereColumn('branch_id', 'stocks.branch_id')
                                 ->when($this->fromDate, fn($q) => $q->whereDate('transaction_date', '>=', $this->fromDate))
                                 ->when($this->toDate, fn($q) => $q->whereDate('transaction_date', '<=', $this->toDate))
                         ]);
                    })
            )
            ->columns([
                TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('productVariant.sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                TextColumn::make('productVariant.name')
                    ->label('Variant')
                    ->searchable()
                    ->sortable()
                    ->default('-'),
                TextColumn::make('productVariant.color.name')
                    ->label('Color')
                    ->sortable()
                    ->default('-'),
                TextColumn::make('productVariant.size.name')
                    ->label('Size')
                    ->sortable()
                    ->default('-'),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->hidden(fn () => (bool) $this->branchId)
                    ->sortable(),
                TextColumn::make('opening_stock')
                    ->label('Opening Stock')
                    ->getStateUsing(function($record) {
                        if ($this->fromDate || $this->toDate) {
                            return ($record->quantity + ($record->stock_out ?? 0)) - ($record->stock_in ?? 0);
                        }
                        return $record->quantity; // If no date filter, opening is same as closing
                    })
                    ->numeric(),
                TextColumn::make('stock_in')
                    ->label('Stock In')
                    ->getStateUsing(fn($record) => $record->stock_in ?? 0)
                    ->numeric(),
                TextColumn::make('stock_out')
                    ->label('Stock Out')
                    ->getStateUsing(fn($record) => $record->stock_out ?? 0)
                    ->numeric(),
                TextColumn::make('reserved_qty')
                    ->label('Reserved')
                    ->numeric(),
                TextColumn::make('quantity')
                    ->label('Closing Stock')
                    ->numeric()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->label('Total Closing Stock')),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(fn () => null)
                    ->extraAttributes(['onclick' => 'window.print()']),
            ])
            ->emptyStateHeading('No inventory records found')
            ->emptyStateDescription('No records found for the selected branch and date range.')
            ->deferLoading()
            ->striped();
    }

    public function getSummaryStatsProperty()
    {
        $query = Stock::query();

        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        } else {
            $userBranchId = session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);
            if ($userBranchId) {
                $query->where('branch_id', $userBranchId);
            }
        }

        $branchName = 'All Branches';
        if ($this->branchId) {
            $branch = \App\Models\Branch::find($this->branchId);
            $branchName = $branch ? $branch->name : 'All Branches';
        }

        return [
            'branch' => $branchName,
            'period' => ($this->fromDate ?: 'Start') . ' - ' . ($this->toDate ?: 'End'),
            'total_items' => $query->count(),
            'total_stock' => $query->sum('quantity'),
        ];
    }

    public function render()
    {
        return view('livewire.inventory-data-report-table', [
            'summary' => $this->summaryStats
        ]);
    }
}
