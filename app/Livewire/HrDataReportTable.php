<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\ExportsReportData;

class HrDataReportTable extends Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable, \Filament\Actions\Contracts\HasActions
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    use \Filament\Tables\Concerns\InteractsWithTable;
    use \Filament\Actions\Concerns\InteractsWithActions;
    use ExportsReportData;

    public $branchId;
    public $fromDate;
    public $toDate;
    public $reportMonth;
    public $reportType;

    protected function getBaseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = \App\Models\Attendance::query()->with(['employee', 'employee.department']);

        if ($this->branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Attendance)->getTable(), 'branch_id')) {
            $query->where('branch_id', $this->branchId);
        } else {
            $userBranchId = session('branch_id') ?? (auth()->check() ? auth()->user()->branch_id : null);
            if ($userBranchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Attendance)->getTable(), 'branch_id')) {
                $query->where('branch_id', $userBranchId);
            }
        }

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $query->whereDate('date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->whereDate('date', '<=', $this->toDate);
        } elseif ($this->reportMonth) {
            if (strlen($this->reportMonth) === 7) {
                $query->whereYear('date', substr($this->reportMonth, 0, 4))
                    ->whereMonth('date', substr($this->reportMonth, 5, 2));
            } else {
                $query->whereDate('date', $this->reportMonth);
            }
        }

        return $query;
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->query($this->getBaseQuery())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                \Filament\Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('employee.employee_id')
                    ->label('Employee Code')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('employee.first_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->employee->first_name . ' ' . $record->employee->last_name),
                \Filament\Tables\Columns\TextColumn::make('employee.department.name')
                    ->label('Department')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'half_day' => 'warning',
                        'late' => 'warning',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('check_in')
                    ->label('Check In')
                    ->time(),
                \Filament\Tables\Columns\TextColumn::make('check_out')
                    ->label('Check Out')
                    ->time(),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(fn () => null)
                    ->extraAttributes(['onclick' => 'window.print()']),
            ])
            ->defaultSort('date', 'desc')
            ->emptyStateHeading('No HR/Attendance records found')
            ->emptyStateDescription('No records found for the selected branch and date range.')
            ->deferLoading()
            ->striped();
    }

    public function getSummaryStatsProperty()
    {
        $query = clone clone clone $this->getBaseQuery();
        $totalRecords = $query->count();
        $present = (clone clone $query)->where('status', 'present')->count();
        $absent = (clone $query)->where('status', 'absent')->count();

        $branchName = 'All Branches';
        if ($this->branchId) {
            $branch = \App\Models\Branch::find($this->branchId);
            $branchName = $branch ? $branch->name : 'All Branches';
        }

        return [
            'branch' => $branchName,
            'period' => ($this->fromDate ?: 'Start') . ' - ' . ($this->toDate ?: 'End'),
            'total_attendance_records' => $totalRecords,
            'total_present' => $present,
            'total_absent' => $absent,
        ];
    }

    public function render()
    {
        return view('livewire.hr-data-report-table', [
            'summary' => $this->summaryStats
        ]);
    }
}
