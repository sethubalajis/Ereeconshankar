<?php

namespace App\Livewire;

use App\Models\PurchaseOrderItem; // Assuming this model or PurchaseItem
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
use App\Services\DateTimeFormatter;
use Illuminate\Database\Eloquent\Builder;

class PurchaseDataReportTable extends Component implements HasForms, HasTable, HasActions
{
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
                \App\Models\PurchaseOrderItem::query()
                    ->with(['purchaseOrder', 'purchaseOrder.vendor', 'product', 'productVariant', 'branch'])
                    ->whereHas('purchaseOrder', function (Builder $query) {
                        if ($this->branchId) {
                            $query->where('branch_id', $this->branchId);
                        } else {
                            $userBranchId = session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);
                            if ($userBranchId) {
                                $query->where('branch_id', $userBranchId);
                            }
                        }
                        if ($this->fromDate && $this->toDate) {
                            $query->whereBetween('po_date', [$this->fromDate, $this->toDate]);
                        } elseif ($this->fromDate) {
                            $query->whereDate('po_date', '>=', $this->fromDate);
                        } elseif ($this->toDate) {
                            $query->whereDate('po_date', '<=', $this->toDate);
                        } elseif ($this->reportDate) {
                            $query->whereDate('po_date', $this->reportDate);
                        }
                    })
            )
            ->columns([
                TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                TextColumn::make('purchaseOrder.po_number')
                    ->label('PO/Invoice No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('purchaseOrder.po_date')
                    ->label('Date')
                    ->formatStateUsing(fn ($state) => DateTimeFormatter::formatDate($state))
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->hidden(fn () => (bool) $this->branchId)
                    ->sortable(),
                TextColumn::make('purchaseOrder.vendor.name')
                    ->label('Vendor')
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
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->getStateUsing(fn ($record) => $record->unit_price * $record->quantity)
                    ->money('inr'),
                TextColumn::make('gst')
                    ->label('GST')
                    ->getStateUsing(fn ($record) => $record->total_price - ($record->unit_price * $record->quantity))
                    ->money('inr'),
                TextColumn::make('total_price')
                    ->label('Total')
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
            ->emptyStateHeading('No purchase records found')
            ->emptyStateDescription('No records found for the selected branch and date range.')
            ->deferLoading()
            ->striped();
    }

    public function getSummaryStatsProperty()
    {
        $query = \App\Models\PurchaseOrder::query();

        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        } else {
            $userBranchId = session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);
            if ($userBranchId) {
                $query->where('branch_id', $userBranchId);
            }
        }

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('po_date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $query->whereDate('po_date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->whereDate('po_date', '<=', $this->toDate);
        } elseif ($this->reportDate) {
            $query->whereDate('po_date', $this->reportDate);
        }

        $branchName = 'All Branches';
        if ($this->branchId) {
            $branch = \App\Models\Branch::find($this->branchId);
            $branchName = $branch ? $branch->name : 'All Branches';
        }

        $totalItems = 0; // In a real app we'd do a join/withSum, keeping it simple
        $totalPurchases = $query->sum('total_amount');

        return [
            'branch' => $branchName,
            'period' => ($this->fromDate ?: 'Start') . ' - ' . ($this->toDate ?: 'End'),
            'total_orders' => $query->count(),
            'total_purchases' => $totalPurchases,
        ];
    }

    public function render()
    {
        return view('livewire.purchase-data-report-table', [
            'summary' => $this->summaryStats
        ]);
    }
}
