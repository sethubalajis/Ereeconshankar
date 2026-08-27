<?php

namespace App\Livewire;

use Livewire\Component;

use \Filament\Tables\Columns\TextColumn;
use \Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use \App\Traits\ExportsReportData;

class FinancialDataReportTable extends Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable, \Filament\Actions\Contracts\HasActions
{
    use ExportsReportData;
    use \Filament\Forms\Concerns\InteractsWithForms;
    use \Filament\Tables\Concerns\InteractsWithTable;
    use \Filament\Actions\Concerns\InteractsWithActions;

    public $reportType;
    public $fromDate;
    public $toDate;
    public $asOfDate;

    protected function getBaseQuery()
    {
        $reportType = $this->reportType ?? 'Profit & Loss';
        $fromDate = $this->fromDate ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $this->toDate ?? now()->endOfMonth()->format('Y-m-d');
        $asOfDate = $this->asOfDate ?? $toDate;

        if ($reportType === 'Profit & Loss' || $reportType === 'Cash Flow') {
            $sales = \App\Models\PosSale::query()
                ->selectRaw("CONCAT('sale-', id) as id, 'Sales Revenue' as category, sale_date as date, invoice_number as doc_no, 'Customer Sale Receipt' as description, total_amount as amount, 'Revenue' as type");
            if ($fromDate && $toDate) {
                $sales->whereBetween('sale_date', [$fromDate, $toDate]);
            }

            $po = \App\Models\PurchaseOrder::query()
                ->selectRaw("CONCAT('po-', id) as id, 'Cost of Goods Sold' as category, po_date as date, po_number as doc_no, 'Vendor Purchase Order' as description, total_amount as amount, 'COGS' as type");
            if ($fromDate && $toDate) {
                $po->whereBetween('po_date', [$fromDate, $toDate]);
            }

            $exp = \App\Models\BranchExpense::query()
                ->selectRaw("CONCAT('exp-', id) as id, 'Operating Expense' as category, expense_date as date, CONCAT('EXP-', id) as doc_no, COALESCE(description, 'Expense') as description, amount, 'Expense' as type");
            if ($fromDate && $toDate) {
                $exp->whereBetween('expense_date', [$fromDate, $toDate]);
            }

            if ($reportType === 'Profit & Loss') {
                $je = \App\Models\JournalEntry::query()
                    ->join('journals', 'journals.id', '=', 'journal_entries.journal_id')
                    ->join('chart_of_accounts', 'chart_of_accounts.id', '=', 'journal_entries.chart_of_account_id')
                    ->selectRaw("CONCAT('je-', journal_entries.id) as id, 'Journal Adjustment' as category, journals.journal_date as date, journals.journal_number as doc_no, COALESCE(journal_entries.description, chart_of_accounts.account_name) as description, CASE WHEN debit > 0 THEN debit ELSE credit END as amount, CASE WHEN debit > 0 THEN 'Expense' ELSE 'Revenue' END as type");
                
                if ($fromDate && $toDate) {
                    $je->whereBetween('journals.journal_date', [$fromDate, $toDate]);
                }

                $union = $sales->unionAll($po)->unionAll($exp)->unionAll($je);
            } else {
                $union = $sales->unionAll($po)->unionAll($exp);
            }

            return \App\Models\PosSale::query()->fromSub($union, 'pos_sales');
        } 
        
        if ($reportType === 'Trial Balance') {
            $accounts = \App\Models\ChartOfAccount::query()
                ->leftJoin('journal_entries', 'chart_of_accounts.id', '=', 'journal_entries.chart_of_account_id')
                ->leftJoin('journals', 'journals.id', '=', 'journal_entries.journal_id');

            if ($fromDate && $toDate) {
                $accounts->whereBetween('journals.journal_date', [$fromDate, $toDate]);
            }
            
            // Due to grouping in Filament, we'll just do a raw group by
            $query = \App\Models\ChartOfAccount::query()
                ->fromSub(
                    DB::table('chart_of_accounts')
                        ->leftJoin('journal_entries', 'chart_of_accounts.id', '=', 'journal_entries.chart_of_account_id')
                        ->leftJoin('journals', 'journals.id', '=', 'journal_entries.journal_id')
                        ->selectRaw("chart_of_accounts.id as id, COALESCE(chart_of_accounts.account_code, CONCAT('ACC-', chart_of_accounts.id)) as account_code, chart_of_accounts.account_name as account_name, COALESCE(chart_of_accounts.account_type, 'General') as account_type, SUM(COALESCE(journal_entries.debit, 0)) as debit, SUM(COALESCE(journal_entries.credit, 0)) as credit")
                        ->when($fromDate && $toDate, fn($q) => $q->whereBetween('journals.journal_date', [$fromDate, $toDate]))
                        ->groupBy('chart_of_accounts.id', 'chart_of_accounts.account_code', 'chart_of_accounts.account_name', 'chart_of_accounts.account_type'),
                    'pos_sales' // Changed to pos_sales to prevent unknown column pos_sales.id
                );

            return $query;
        }

        if ($reportType === 'Balance Sheet') {
            // For Balance Sheet, calculating all sums dynamically into a table is complex in SQL.
            // We use literal queries
            $data = \App\Helpers\ReportPdfHelper::getFinancialReportData($reportType, $fromDate, $toDate, $asOfDate);
            $items = $data['items'] ?? [];
            
            $literalQuery = null;
            foreach ($items as $idx => $item) {
                $q = DB::query()->selectRaw(
                    "? as id, ? as category, ? as date, ? as doc_no, ? as description, ? as amount, ? as type",
                    ["bs-".$idx, $item['category'], $item['date'], $item['doc_no'], $item['description'], $item['amount'], $item['type']]
                );
                if ($literalQuery === null) {
                    $literalQuery = $q;
                } else {
                    $literalQuery->unionAll($q);
                }
            }
            
            if ($literalQuery === null) {
                // empty query
                $literalQuery = DB::query()->selectRaw("'' as id, '' as category, '' as date, '' as doc_no, '' as description, 0 as amount, '' as type")->whereRaw('1 = 0');
            }

            return \App\Models\PosSale::query()->fromSub($literalQuery, 'pos_sales');
        }

        return \App\Models\PosSale::query()->whereId(-1);
    }

    public function table(Table $table): Table
    {
        $reportType = $this->reportType ?? 'Profit & Loss';
        $isTrialBalance = $reportType === 'Trial Balance';

        return $table
            ->query($this->getBaseQuery())
            ->columns([
                TextColumn::make('index')
                    ->label('S.No')
                    ->rowIndex(),
                
                // Fields for P&L, Cash Flow, Balance Sheet
                TextColumn::make('category')
                    ->label('Category')
                    ->searchable()
                    ->sortable()
                    ->visible(! $isTrialBalance),
                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->visible(! $isTrialBalance),
                TextColumn::make('doc_no')
                    ->label('Ref / Doc No')
                    ->searchable()
                    ->sortable()
                    ->visible(! $isTrialBalance),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->visible(! $isTrialBalance),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->visible(! $isTrialBalance),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('inr')
                    ->sortable()
                    ->visible(! $isTrialBalance)
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('Total Amount')),
                    
                // Fields for Trial Balance
                TextColumn::make('account_code')
                    ->label('Account Code')
                    ->searchable()
                    ->sortable()
                    ->visible($isTrialBalance),
                TextColumn::make('account_name')
                    ->label('Account Name')
                    ->searchable()
                    ->sortable()
                    ->visible($isTrialBalance),
                TextColumn::make('account_type')
                    ->label('Type')
                    ->badge()
                    ->visible($isTrialBalance),
                TextColumn::make('debit')
                    ->label('Debit')
                    ->money('inr')
                    ->sortable()
                    ->visible($isTrialBalance)
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('Total Debit')),
                TextColumn::make('credit')
                    ->label('Credit')
                    ->money('inr')
                    ->sortable()
                    ->visible($isTrialBalance)
                    ->summarize(\Filament\Tables\Columns\Summarizers\Sum::make()->money('inr')->label('Total Credit')),
            ])
            ->emptyStateHeading('No financial records found')
            ->emptyStateDescription('No records found for the selected report type and period.')
            ->deferLoading()
            ->striped();
    }

    public function getSummaryStatsProperty()
    {
        $data = \App\Helpers\ReportPdfHelper::getFinancialReportData(
            $this->reportType, 
            $this->fromDate, 
            $this->toDate, 
            $this->asOfDate
        );
        return array_merge(['report_type' => $this->reportType ?? 'Profit & Loss'], $data['summary'] ?? []);
    }

    public function render()
    {
        return view('livewire.financial-data-report-table', [
            'summary' => $this->summaryStats,
            'reportType' => $this->reportType ?? 'Profit & Loss',
        ]);
    }
}
