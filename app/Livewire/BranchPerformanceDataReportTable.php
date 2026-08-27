<?php

namespace App\Livewire;

use App\Models\PosSale;
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
use App\Traits\ExportsReportData;

class BranchPerformanceDataReportTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;
    use ExportsReportData;

    public $branchId;
    public $fromDate;
    public $toDate;
    public $reportMonth;

    protected function getBaseQuery(): Builder
    {
        $query = PosSale::query()
            ->select([
                'branch_id as id',
                'branch_id',
                \Illuminate\Support\Facades\DB::raw('COUNT(id) as total_orders'),
                \Illuminate\Support\Facades\DB::raw('SUM(total_amount) as total_sales'),
                \Illuminate\Support\Facades\DB::raw('SUM(discount_amount) as total_discount'),
                \Illuminate\Support\Facades\DB::raw('SUM(tax_amount) as total_gst'),
                \Illuminate\Support\Facades\DB::raw('SUM(net_amount) as net_sales'),
            ])
            ->groupBy('branch_id');

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
        } elseif ($this->reportMonth) {
            if (strlen($this->reportMonth) === 7) {
                $query->whereYear('sale_date', substr($this->reportMonth, 0, 4))
                    ->whereMonth('sale_date', substr($this->reportMonth, 5, 2));
            } else {
                $query->whereDate('sale_date', $this->reportMonth);
            }
        }

        return PosSale::query()->fromSub($query, 'pos_sales')->with('branch');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getBaseQuery())
            ->columns([
                TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->hidden(fn () => (bool) $this->branchId),
                TextColumn::make('total_orders')
                    ->label('Total Orders')
                    ->numeric()
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->label('TOTAL')),
                TextColumn::make('total_sales')
                    ->label('Total Sales')
                    ->money('inr')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('total_discount')
                    ->label('Discount')
                    ->money('inr')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('total_gst')
                    ->label('GST')
                    ->money('inr')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
                TextColumn::make('net_sales')
                    ->label('Net Sales')
                    ->money('inr')
                    ->sortable()
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('')),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(fn () => null)
                    ->extraAttributes(['onclick' => 'window.print()']),
            ])
            ->emptyStateHeading('No branch performance records found')
            ->emptyStateDescription('No records found for the selected date range.')
            ->deferLoading()
            ->striped()
            ->defaultSort('branch_id');
    }

    public function getSummaryStatsProperty()
    {
        $records = $this->getBaseQuery()->get();
        
        $branchName = 'All Branches';
        if ($this->branchId) {
            $branch = \App\Models\Branch::find($this->branchId);
            $branchName = $branch ? $branch->name : 'All Branches';
        }

        return [
            'branch' => $branchName,
            'period' => ($this->fromDate ?: 'Start') . ' - ' . ($this->toDate ?: 'End'),
            'total_invoices' => $records->sum('total_orders'),
            'total_net' => (float) $records->sum('net_sales'),
            'total_discount' => (float) $records->sum('total_discount'),
        ];
    }

    public function render()
    {
        return view('livewire.branch-performance-data-report-table', [
            'summary' => $this->summaryStats
        ]);
    }
}
