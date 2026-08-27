<?php

namespace App\Livewire;

use Livewire\Component;
use \App\Traits\ExportsReportData;

class KpiDataReportTable extends Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable, \Filament\Actions\Contracts\HasActions
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    use \Filament\Tables\Concerns\InteractsWithTable;
    use \Filament\Actions\Concerns\InteractsWithActions;
    use \App\Traits\ExportsReportData;

    public $reportType;
    public $kpiName;
    public $fromDate;
    public $toDate;
    public $reportDate;
    public $value;

    protected function getBaseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $kpiName = $this->kpiName ?: 'N/A';
        $periodStr = ($this->fromDate && $this->toDate) ? ($this->fromDate . ' to ' . $this->toDate) : ($this->fromDate ?? $this->toDate ?? $this->reportDate ?? 'N/A');
        $val = (float)($this->value ?? 0);

        $sub = \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw('DUAL'))
            ->selectRaw("1 as id, ? as kpi_name, ? as period, ? as calculated_value", [$kpiName, $periodStr, $val]);

        return \App\Models\User::query()->fromSub($sub, 'users');
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->query($this->getBaseQuery())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('kpi_name')
                    ->label('KPI Metric')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('period')
                    ->label('Period')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('calculated_value')
                    ->label('Calculated Value')
                    ->numeric(2)
                    ->sortable()
                    ->badge()
                    ->color('info'),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->action(fn () => null)
                    ->extraAttributes(['onclick' => 'window.print()']),
            ])
            ->emptyStateHeading('No KPI records found')
            ->deferLoading()
            ->striped()
            ->paginated(false);
    }

    public function render()
    {
        return view('livewire.kpi-data-report-table');
    }
}
