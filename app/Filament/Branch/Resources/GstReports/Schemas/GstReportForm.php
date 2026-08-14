<?php

namespace App\Filament\Branch\Resources\GstReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;


class GstReportForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateSummaryData = function ($get, $set) {
            $reportType = $get('report_type');
            $fromDate = $get('period_start');
            $toDate = $get('period_end');

            if ($reportType || $fromDate || $toDate) {
                $data = \App\Helpers\ReportPdfHelper::getGstReportData($reportType, $fromDate, $toDate);
                $summary = $data['summary'];
                $summaryText = sprintf(
                    "Report Type: %s | Period: %s to %s | Total Invoices: %d | Taxable Value: ₹%s | CGST: ₹%s | SGST: ₹%s | IGST: ₹%s | Total Tax: ₹%s | Total Amount: ₹%s",
                    $reportType ?? 'GSTR-1',
                    $fromDate ?? 'N/A',
                    $toDate ?? 'N/A',
                    $summary['invoice_count'],
                    number_format($summary['taxable_amount'], 2),
                    number_format($summary['cgst'], 2),
                    number_format($summary['sgst'], 2),
                    number_format($summary['igst'], 2),
                    number_format($summary['tax_amount'], 2),
                    number_format($summary['total_amount'], 2)
                );
                $set('summary_data', $summaryText);
            }
        };

        return $schema
            ->components([
                Select::make('report_type')
                    ->options([
                        'GSTR-1' => 'GSTR-1 (Outward Supplies)',
                        'GSTR-2A' => 'GSTR-2A (Inward Supplies Read-Only)',
                        'GSTR-2B' => 'GSTR-2B (Input Tax Credit Statement)',
                        'GSTR-3B' => 'GSTR-3B (Monthly Summary Return)',
                        'GSTR-4' => 'GSTR-4 (Composition Return)',
                        'GSTR-9' => 'GSTR-9 (Annual Return)',
                        'GSTR-9C' => 'GSTR-9C (Reconciliation Statement)',
                    ])
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated($updateSummaryData),
                DatePicker::make('period_start')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateSummaryData),
                DatePicker::make('period_end')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateSummaryData),
                Textarea::make('summary_data')
                    ->columnSpanFull(),
            
                Placeholder::make('report_details')
                    ->label('GST Report Details & Tax Breakdown')
                    ->content(function ($get) {
                        $reportType = $get('report_type');
                        $fromDate = $get('period_start');
                        $toDate = $get('period_end');
                        
                        if (!$fromDate && !$toDate && !$reportType) {
                            return new HtmlString('<p class="text-gray-500">Please select report type and period date range to view GST details.</p>');
                        }

                        $data = \App\Helpers\ReportPdfHelper::getGstReportData($reportType, $fromDate, $toDate);
                        $summary = $data['summary'];
                        $items = $data['items'];

                        $html = '<div class="space-y-4">';
                        
                        // Summary statistics grid
                        $html .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-emerald-50 p-4 rounded-lg border border-emerald-200">';
                        $html .= '<div><span class="text-xs text-emerald-700 block uppercase font-bold">Total Invoices</span><span class="text-lg font-bold text-emerald-900">' . $summary['invoice_count'] . '</span></div>';
                        $html .= '<div><span class="text-xs text-emerald-700 block uppercase font-bold">Taxable Value</span><span class="text-lg font-bold text-emerald-900">₹' . number_format($summary['taxable_amount'], 2) . '</span></div>';
                        $html .= '<div><span class="text-xs text-emerald-700 block uppercase font-bold">CGST / SGST</span><span class="text-lg font-bold text-emerald-900">₹' . number_format($summary['cgst'], 2) . ' / ₹' . number_format($summary['sgst'], 2) . '</span></div>';
                        $html .= '<div><span class="text-xs text-emerald-700 block uppercase font-bold">Total Tax Amount</span><span class="text-lg font-bold text-emerald-900">₹' . number_format($summary['tax_amount'], 2) . '</span></div>';
                        $html .= '</div>';

                        if (empty($items)) {
                            $html .= '<p class="text-gray-500 py-4">No GST invoice transactions found for the selected period range and report type.</p></div>';
                            return new HtmlString($html);
                        }

                        $html .= '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse border border-gray-200">';
                        $html .= '<thead><tr class="bg-gray-100">';
                        $html .= '<th class="p-2 border">#</th>';
                        $html .= '<th class="p-2 border">Doc / Invoice No</th>';
                        $html .= '<th class="p-2 border">Date</th>';
                        $html .= '<th class="p-2 border">Type</th>';
                        $html .= '<th class="p-2 border text-right">Taxable Value</th>';
                        $html .= '<th class="p-2 border text-right">CGST</th>';
                        $html .= '<th class="p-2 border text-right">SGST</th>';
                        $html .= '<th class="p-2 border text-right">IGST</th>';
                        $html .= '<th class="p-2 border text-right">Total Tax</th>';
                        $html .= '<th class="p-2 border text-right">Invoice Total</th>';
                        $html .= '</tr></thead><tbody>';

                        foreach ($items as $idx => $item) {
                            $html .= '<tr>';
                            $html .= '<td class="p-2 border">' . ($idx + 1) . '</td>';
                            $html .= '<td class="p-2 border font-semibold">' . htmlspecialchars($item['doc_no']) . '</td>';
                            $html .= '<td class="p-2 border">' . date('d/m/Y', strtotime($item['date'])) . '</td>';
                            $badgeClass = $item['type'] === 'Sales' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800';
                            $html .= '<td class="p-2 border"><span class="px-2 py-0.5 text-xs rounded font-bold ' . $badgeClass . '">' . $item['type'] . '</span></td>';
                            $html .= '<td class="p-2 border text-right">₹' . number_format($item['taxable'], 2) . '</td>';
                            $html .= '<td class="p-2 border text-right">₹' . number_format($item['cgst'], 2) . '</td>';
                            $html .= '<td class="p-2 border text-right">₹' . number_format($item['sgst'], 2) . '</td>';
                            $html .= '<td class="p-2 border text-right">₹' . number_format($item['igst'], 2) . '</td>';
                            $html .= '<td class="p-2 border text-right font-semibold">₹' . number_format($item['tax_amount'], 2) . '</td>';
                            $html .= '<td class="p-2 border text-right font-bold">₹' . number_format($item['total_amount'], 2) . '</td>';
                            $html .= '</tr>';
                        }

                        $html .= '</tbody><tfoot><tr class="bg-gray-100 font-bold">';
                        $html .= '<td colspan="4" class="p-2 border text-right">Total Summary:</td>';
                        $html .= '<td class="p-2 border text-right">₹' . number_format($summary['taxable_amount'], 2) . '</td>';
                        $html .= '<td class="p-2 border text-right">₹' . number_format($summary['cgst'], 2) . '</td>';
                        $html .= '<td class="p-2 border text-right">₹' . number_format($summary['sgst'], 2) . '</td>';
                        $html .= '<td class="p-2 border text-right">₹' . number_format($summary['igst'], 2) . '</td>';
                        $html .= '<td class="p-2 border text-right">₹' . number_format($summary['tax_amount'], 2) . '</td>';
                        $html .= '<td class="p-2 border text-right text-emerald-700">₹' . number_format($summary['total_amount'], 2) . '</td>';
                        $html .= '</tr></tfoot></table></div></div>';

                        return new HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
