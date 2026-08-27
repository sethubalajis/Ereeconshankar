<?php

namespace App\Livewire;

use App\Models\StockTransaction;
use App\Models\Branch;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class StockTransactionDataReportTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;
    use \App\Traits\ExportsReportData;

    public $branchId;
    public $fromDate;
    public $toDate;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                StockTransaction::query()
                    ->with(['product', 'branch'])
                    ->when($this->branchId, fn ($q) => $q->where('branch_id', $this->branchId))
                    ->when($this->fromDate, fn ($q) => $q->whereDate('transaction_date', '>=', $this->fromDate))
                    ->when($this->toDate, fn ($q) => $q->whereDate('transaction_date', '<=', $this->toDate))
            )
            ->columns([
                TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                TextColumn::make('reference')
                    ->label('Transaction No / Ref')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'adjustment' => 'warning',
                        'transfer' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('inward_qty')
                    ->label('Qty In')
                    ->numeric()
                    ->state(fn ($record) => $record->transaction_type === 'in' ? $record->quantity : null),
                TextColumn::make('outward_qty')
                    ->label('Qty Out')
                    ->numeric()
                    ->state(fn ($record) => $record->transaction_type === 'out' ? $record->quantity : null),
            ])
            ->filters([
                // Filters are now handled by the form
            ])
            ->headerActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(fn () => null)
                    ->extraAttributes(['onclick' => 'window.print()']),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->deferLoading()
            ->striped();
    }

    public function render()
    {
        return view('livewire.stock-transaction-data-report-table');
    }
}
