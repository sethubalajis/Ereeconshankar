<?php

namespace App\Filament\Branch\Resources\FinancialReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class FinancialReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('report_type')
                    ->options([
                        'Profit & Loss' => 'Profit & Loss',
                        'Balance Sheet' => 'Balance Sheet',
                        'Cash Flow' => 'Cash Flow',
                        'Trial Balance' => 'Trial Balance',
                    ])
                    ->required()
                    ->live(),
                DatePicker::make('from_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('to_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live(),
                DatePicker::make('as_of_date')
                    ->label('As Of Date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable()
                    ->live(),
                Textarea::make('data')
                    ->columnSpanFull(),
            
                Placeholder::make('report_details')
                    ->label('Report Details & Live Financial Breakdown')
                    ->content(function ($get) {
                        $reportType = $get('report_type');
                        $fromDate = $get('from_date');
                        $toDate = $get('to_date');
                        $asOfDate = $get('as_of_date');

                        if (!$reportType) {
                            return new HtmlString('<p class="text-gray-500">Please select a report type to view details.</p>');
                        }

                        if (!$fromDate && !$toDate && !$asOfDate) {
                            return new HtmlString('<p class="text-gray-500">Please select a date range or as of date to view details.</p>');
                        }

                        $data = \App\Helpers\ReportPdfHelper::getFinancialReportData($reportType, $fromDate, $toDate, $asOfDate);
                        $summary = $data['summary'] ?? [];
                        $items = $data['items'] ?? [];

                        $html = '<div class="space-y-4">';

                        // 1. KPI Summary Cards Grid
                        $html .= '<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">';
                        if ($reportType === 'Profit & Loss') {
                            $rev = number_format($summary['total_revenue'] ?? 0, 2);
                            $exp = number_format($summary['operating_expenses'] ?? 0, 2);
                            $net = number_format($summary['net_profit'] ?? 0, 2);
                            $isProf = ($summary['net_profit'] ?? 0) >= 0;

                            $html .= '<div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg"><div class="text-xs text-emerald-600 font-semibold uppercase">Total Revenue</div><div class="text-xl font-bold text-emerald-700">₹' . $rev . '</div></div>';
                            $html .= '<div class="p-4 bg-rose-50 border border-rose-200 rounded-lg"><div class="text-xs text-rose-600 font-semibold uppercase">Operating Expenses</div><div class="text-xl font-bold text-rose-700">₹' . $exp . '</div></div>';
                            $html .= '<div class="p-4 ' . ($isProf ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200') . ' border rounded-lg"><div class="text-xs ' . ($isProf ? 'text-green-600' : 'text-red-600') . ' font-semibold uppercase">Net ' . ($isProf ? 'Profit' : 'Loss') . '</div><div class="text-xl font-bold ' . ($isProf ? 'text-green-700' : 'text-red-700') . '">₹' . $net . '</div></div>';
                        } elseif ($reportType === 'Balance Sheet') {
                            $ast = number_format($summary['total_assets'] ?? 0, 2);
                            $lia = number_format($summary['total_liabilities'] ?? 0, 2);
                            $eqt = number_format($summary['total_equity'] ?? 0, 2);

                            $html .= '<div class="p-4 bg-blue-50 border border-blue-200 rounded-lg"><div class="text-xs text-blue-600 font-semibold uppercase">Total Assets</div><div class="text-xl font-bold text-blue-700">₹' . $ast . '</div></div>';
                            $html .= '<div class="p-4 bg-amber-50 border border-amber-200 rounded-lg"><div class="text-xs text-amber-600 font-semibold uppercase">Total Liabilities</div><div class="text-xl font-bold text-amber-700">₹' . $lia . '</div></div>';
                            $html .= '<div class="p-4 bg-purple-50 border border-purple-200 rounded-lg"><div class="text-xs text-purple-600 font-semibold uppercase">Total Equity</div><div class="text-xl font-bold text-purple-700">₹' . $eqt . '</div></div>';
                        } elseif ($reportType === 'Cash Flow') {
                            $inf = number_format($summary['total_inflow'] ?? 0, 2);
                            $out = number_format($summary['total_outflow'] ?? 0, 2);
                            $net = number_format($summary['net_cash_flow'] ?? 0, 2);

                            $html .= '<div class="p-4 bg-teal-50 border border-teal-200 rounded-lg"><div class="text-xs text-teal-600 font-semibold uppercase">Total Cash Inflows</div><div class="text-xl font-bold text-teal-700">₹' . $inf . '</div></div>';
                            $html .= '<div class="p-4 bg-orange-50 border border-orange-200 rounded-lg"><div class="text-xs text-orange-600 font-semibold uppercase">Total Cash Outflows</div><div class="text-xl font-bold text-orange-700">₹' . $out . '</div></div>';
                            $html .= '<div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg"><div class="text-xs text-indigo-600 font-semibold uppercase">Net Cash Flow</div><div class="text-xl font-bold text-indigo-700">₹' . $net . '</div></div>';
                        } else {
                            $deb = number_format($summary['total_debit'] ?? 0, 2);
                            $crd = number_format($summary['total_credit'] ?? 0, 2);
                            $bal = $summary['is_balanced'] ?? false;

                            $html .= '<div class="p-4 bg-sky-50 border border-sky-200 rounded-lg"><div class="text-xs text-sky-600 font-semibold uppercase">Total Debits</div><div class="text-xl font-bold text-sky-700">₹' . $deb . '</div></div>';
                            $html .= '<div class="p-4 bg-cyan-50 border border-cyan-200 rounded-lg"><div class="text-xs text-cyan-600 font-semibold uppercase">Total Credits</div><div class="text-xl font-bold text-cyan-700">₹' . $crd . '</div></div>';
                            $html .= '<div class="p-4 ' . ($bal ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200') . ' border rounded-lg"><div class="text-xs ' . ($bal ? 'text-green-600' : 'text-amber-600') . ' font-semibold uppercase">Trial Balance Status</div><div class="text-xl font-bold ' . ($bal ? 'text-green-700' : 'text-amber-700') . '">' . ($bal ? 'Balanced' : 'Unbalanced') . '</div></div>';
                        }
                        $html .= '</div>';

                        // 2. Data Table
                        if (empty($items)) {
                            $html .= '<p class="text-gray-500 italic">No detailed records found for the selected parameters.</p></div>';
                            return new HtmlString($html);
                        }

                        $html .= '<div class="overflow-x-auto border border-gray-200 rounded-lg"><table class="w-full text-sm text-left border-collapse">';
                        if ($reportType === 'Trial Balance') {
                            $html .= '<thead><tr class="bg-gray-100 text-gray-700"><th class="p-2 border">#</th><th class="p-2 border">Code</th><th class="p-2 border">Account Name</th><th class="p-2 border">Type</th><th class="p-2 border text-right">Debit (₹)</th><th class="p-2 border text-right">Credit (₹)</th></tr></thead><tbody>';
                            foreach ($items as $idx => $item) {
                                $html .= '<tr class="hover:bg-gray-50">';
                                $html .= '<td class="p-2 border">' . ($idx + 1) . '</td>';
                                $html .= '<td class="p-2 border font-medium">' . e($item['account_code'] ?? '-') . '</td>';
                                $html .= '<td class="p-2 border">' . e($item['account_name'] ?? '-') . '</td>';
                                $html .= '<td class="p-2 border"><span class="px-2 py-0.5 text-xs font-semibold rounded bg-gray-100 text-gray-800">' . e($item['account_type'] ?? 'General') . '</span></td>';
                                $html .= '<td class="p-2 border text-right">₹' . number_format($item['debit'] ?? 0, 2) . '</td>';
                                $html .= '<td class="p-2 border text-right">₹' . number_format($item['credit'] ?? 0, 2) . '</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody></table></div></div>';
                        } else {
                            $html .= '<thead><tr class="bg-gray-100 text-gray-700"><th class="p-2 border">#</th><th class="p-2 border">Category</th><th class="p-2 border">Date</th><th class="p-2 border">Ref / Doc No</th><th class="p-2 border">Description</th><th class="p-2 border">Type</th><th class="p-2 border text-right">Amount (₹)</th></tr></thead><tbody>';
                            foreach ($items as $idx => $item) {
                                $html .= '<tr class="hover:bg-gray-50">';
                                $html .= '<td class="p-2 border">' . ($idx + 1) . '</td>';
                                $html .= '<td class="p-2 border font-medium">' . e($item['category'] ?? '-') . '</td>';
                                $html .= '<td class="p-2 border">' . e($item['date'] ?? '-') . '</td>';
                                $html .= '<td class="p-2 border font-mono text-xs">' . e($item['doc_no'] ?? '-') . '</td>';
                                $html .= '<td class="p-2 border">' . e($item['description'] ?? '-') . '</td>';
                                $html .= '<td class="p-2 border"><span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800">' . e($item['type'] ?? '-') . '</span></td>';
                                $html .= '<td class="p-2 border text-right font-semibold">₹' . number_format($item['amount'] ?? 0, 2) . '</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody></table></div></div>';
                        }

                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
