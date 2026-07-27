<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportPdfHelper
{
    public static function downloadSinglePdf(string $title, object $record, array $columns): StreamedResponse
    {
        $data = [];
        foreach ($columns as $key => $label) {
            $val = data_get($record, $key);
            if ($val instanceof \DateTimeInterface) {
                $val = $val->format('Y-m-d H:i');
            } elseif (is_array($val)) {
                $val = json_encode($val);
            }
            $data[$label] = $val ?? '-';
        }

        $pdf = Pdf::loadView('reports.pdf', [
            'title' => $title,
            'records' => [$record],
            'columns' => $columns,
        ]);

        $filename = \Illuminate\Support\Str::slug($title) . '-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function downloadListPdf(string $title, iterable $records, array $columns): StreamedResponse
    {
        $pdf = Pdf::loadView('reports.pdf', [
            'title' => $title,
            'records' => $records,
            'columns' => $columns,
        ]);

        $filename = \Illuminate\Support\Str::slug($title) . '-' . date('Y-m-d') . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function downloadSalesReportPdf(object $record): StreamedResponse
    {
        $query = \App\Models\PosSale::where('branch_id', $record->branch_id);

        if ($record->from_date && $record->to_date) {
            $query->whereBetween('sale_date', [$record->from_date, $record->to_date]);
        } elseif ($record->from_date) {
            $query->whereDate('sale_date', '>=', $record->from_date);
        } elseif ($record->to_date) {
            $query->whereDate('sale_date', '<=', $record->to_date);
        } elseif ($record->report_date) {
            $query->whereDate('sale_date', $record->report_date);
        }

        $sales = $query->get();

        $pdf = Pdf::loadView('reports.sales_report_pdf', [
            'record' => $record,
            'sales' => $sales,
        ]);

        $filename = 'sales-report-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function downloadPurchaseReportPdf(object $record): StreamedResponse
    {
        $fromDate = $record->from_date;
        $toDate = $record->to_date;
        $date = $record->report_date;
        $branchId = $record->branch_id;

        $query = \App\Models\PurchaseOrder::query();

        if ($fromDate && $toDate) {
            $query->whereBetween('po_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('po_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('po_date', '<=', $toDate);
        } elseif ($date) {
            if (strlen($date) === 7) {
                $query->whereYear('po_date', substr($date, 0, 4))
                    ->whereMonth('po_date', substr($date, 5, 2));
            } else {
                $query->whereDate('po_date', $date);
            }
        }

        if ($branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\PurchaseOrder)->getTable(), 'branch_id')) {
            $query->where('branch_id', $branchId);
        }

        $purchaseOrders = $query->with(['vendor', 'branch', 'items.product', 'items.productVariant'])->get();

        $pdf = Pdf::loadView('reports.purchase_report_pdf', [
            'record' => $record,
            'purchaseOrders' => $purchaseOrders,
        ]);

        $filename = 'purchase-report-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function downloadInventoryReportPdf(object $record): StreamedResponse
    {
        $fromDate = $record->from_date;
        $toDate = $record->to_date;
        $date = $record->report_date;
        $branchId = $record->branch_id;

        $branchInventories = \App\Models\BranchInventory::where('branch_id', $branchId)
            ->with(['product', 'productVariant'])
            ->get();

        $stocks = $branchInventories->isEmpty()
            ? \App\Models\Stock::where('branch_id', $branchId)->with(['product', 'productVariant', 'warehouse'])->get()
            : collect();

        $query = \App\Models\InventoryCount::where('branch_id', $branchId);

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        } elseif ($date) {
            if (strlen($date) === 7) {
                $query->whereYear('created_at', substr($date, 0, 4))
                    ->whereMonth('created_at', substr($date, 5, 2));
            } else {
                $query->whereDate('created_at', $date);
            }
        }

        $inventoryCounts = $query->with(['warehouse', 'inventoryReconciliations.product', 'inventoryReconciliations.productVariant'])->get();

        $pdf = Pdf::loadView('reports.inventory_report_pdf', [
            'record' => $record,
            'branchInventories' => $branchInventories,
            'stocks' => $stocks,
            'inventoryCounts' => $inventoryCounts,
        ]);

        $filename = 'inventory-report-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function getGstReportData(?string $reportType, ?string $fromDate, ?string $toDate): array
    {
        $items = [];
        $taxableTotal = 0;
        $cgstTotal = 0;
        $sgstTotal = 0;
        $igstTotal = 0;
        $taxTotal = 0;
        $grossTotal = 0;

        $type = strtoupper($reportType ?? 'GSTR-1');

        // Include Outward Sales for GSTR-1, GSTR-3B, GSTR-9, GSTR-9C, or default
        if (in_array($type, ['GSTR-1', 'GSTR-3B', 'GSTR-9', 'GSTR-9C']) || empty($type)) {
            $salesQuery = \App\Models\PosSale::query();
            if ($fromDate && $toDate) {
                $salesQuery->whereBetween('sale_date', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $salesQuery->whereDate('sale_date', '>=', $fromDate);
            } elseif ($toDate) {
                $salesQuery->whereDate('sale_date', '<=', $toDate);
            }

            foreach ($salesQuery->get() as $sale) {
                $tax = (float) $sale->tax_amount;
                $tot = (float) $sale->total_amount;
                $taxable = $tot - $tax;
                $cgst = $tax / 2;
                $sgst = $tax / 2;
                $igst = 0;

                $taxableTotal += $taxable;
                $cgstTotal += $cgst;
                $sgstTotal += $sgst;
                $igstTotal += $igst;
                $taxTotal += $tax;
                $grossTotal += $tot;

                $items[] = [
                    'doc_no' => $sale->invoice_number,
                    'date' => $sale->sale_date,
                    'type' => 'Sales',
                    'taxable' => $taxable,
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'igst' => $igst,
                    'tax_amount' => $tax,
                    'total_amount' => $tot,
                ];
            }
        }

        // Include Inward Purchases for GSTR-2A, GSTR-2B, GSTR-3B, GSTR-4, GSTR-9, GSTR-9C
        if (in_array($type, ['GSTR-2A', 'GSTR-2B', 'GSTR-3B', 'GSTR-4', 'GSTR-9', 'GSTR-9C'])) {
            $poQuery = \App\Models\PurchaseOrder::query();
            if ($fromDate && $toDate) {
                $poQuery->whereBetween('po_date', [$fromDate, $toDate]);
            } elseif ($fromDate) {
                $poQuery->whereDate('po_date', '>=', $fromDate);
            } elseif ($toDate) {
                $poQuery->whereDate('po_date', '<=', $toDate);
            }

            foreach ($poQuery->get() as $po) {
                $tot = (float) $po->total_amount;
                $tax = (float) ($po->tax_amount ?? ($tot * 0.18));
                $taxable = $tot - $tax;
                $cgst = $tax / 2;
                $sgst = $tax / 2;
                $igst = 0;

                $taxableTotal += $taxable;
                $cgstTotal += $cgst;
                $sgstTotal += $sgst;
                $igstTotal += $igst;
                $taxTotal += $tax;
                $grossTotal += $tot;

                $items[] = [
                    'doc_no' => $po->po_number ?? ('PO-' . $po->id),
                    'date' => $po->po_date ?? $po->created_at,
                    'type' => 'Purchase',
                    'taxable' => $taxable,
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'igst' => $igst,
                    'tax_amount' => $tax,
                    'total_amount' => $tot,
                ];
            }
        }

        return [
            'summary' => [
                'invoice_count' => count($items),
                'taxable_amount' => $taxableTotal,
                'cgst' => $cgstTotal,
                'sgst' => $sgstTotal,
                'igst' => $igstTotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $grossTotal,
            ],
            'items' => $items,
        ];
    }

    public static function downloadGstReportPdf(object $record): StreamedResponse
    {
        $fromDate = $record->period_start;
        $toDate = $record->period_end;
        $reportType = $record->report_type;

        $data = static::getGstReportData($reportType, $fromDate, $toDate);

        $pdf = Pdf::loadView('reports.gst_report_pdf', [
            'record' => $record,
            'summary' => $data['summary'],
            'items' => $data['items'],
        ]);

        $filename = 'gst-report-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }
}
