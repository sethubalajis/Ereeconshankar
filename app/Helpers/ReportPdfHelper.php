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

    public static function getFinancialReportData(?string $reportType, ?string $fromDate, ?string $toDate, ?string $asOfDate = null): array
    {
        $reportType = $reportType ?? 'Profit & Loss';
        $fromDate = $fromDate ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $toDate ?? now()->endOfMonth()->format('Y-m-d');
        $asOfDate = $asOfDate ?? $toDate;

        $summary = [];
        $items = [];

        switch ($reportType) {
            case 'Profit & Loss':
                // Revenue: POS Sales
                $salesQuery = \App\Models\PosSale::query();
                if ($fromDate && $toDate) {
                    $salesQuery->whereBetween('sale_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $salesQuery->whereDate('sale_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $salesQuery->whereDate('sale_date', '<=', $toDate);
                }
                $sales = $salesQuery->get();
                $totalSales = (float) $sales->sum('total_amount');

                // Cost of Goods Sold / Purchases: Purchase Orders
                $poQuery = \App\Models\PurchaseOrder::query();
                if ($fromDate && $toDate) {
                    $poQuery->whereBetween('po_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $poQuery->whereDate('po_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $poQuery->whereDate('po_date', '<=', $toDate);
                }
                $purchaseOrders = $poQuery->get();
                $totalPurchases = (float) $purchaseOrders->sum('total_amount');

                // Operating Expenses: Branch Expenses
                $expenseQuery = \App\Models\BranchExpense::query();
                if ($fromDate && $toDate) {
                    $expenseQuery->whereBetween('expense_date', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $expenseQuery->whereDate('expense_date', '>=', $fromDate);
                } elseif ($toDate) {
                    $expenseQuery->whereDate('expense_date', '<=', $toDate);
                }
                $expenses = $expenseQuery->get();
                $totalExpenses = (float) $expenses->sum('amount');

                // Check Journal Entries if available
                $journalEntries = \App\Models\JournalEntry::whereHas('journal', function ($q) use ($fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $q->whereBetween('journal_date', [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $q->whereDate('journal_date', '>=', $fromDate);
                    } elseif ($toDate) {
                        $q->whereDate('journal_date', '<=', $toDate);
                    }
                })->with('chartOfAccount')->get();

                $journalIncome = 0;
                $journalExpense = 0;
                foreach ($journalEntries as $entry) {
                    $accountType = strtolower($entry->chartOfAccount->account_type ?? '');
                    if (str_contains($accountType, 'income') || str_contains($accountType, 'revenue')) {
                        $journalIncome += (float) ($entry->credit - $entry->debit);
                    } elseif (str_contains($accountType, 'expense') || str_contains($accountType, 'cogs')) {
                        $journalExpense += (float) ($entry->debit - $entry->credit);
                    }
                }

                $totalRevenue = $totalSales + max(0, $journalIncome);
                $grossProfit = $totalRevenue - $totalPurchases;
                $operatingExpenses = $totalExpenses + max(0, $journalExpense);
                $netProfit = $grossProfit - $operatingExpenses;

                $summary = [
                    'total_revenue' => $totalRevenue,
                    'cogs_purchases' => $totalPurchases,
                    'gross_profit' => $grossProfit,
                    'operating_expenses' => $operatingExpenses,
                    'net_profit' => $netProfit,
                ];

                foreach ($sales as $sale) {
                    $items[] = [
                        'category' => 'Sales Revenue',
                        'date' => $sale->sale_date ? (is_string($sale->sale_date) ? $sale->sale_date : $sale->sale_date->format('Y-m-d')) : '-',
                        'doc_no' => $sale->invoice_number,
                        'description' => 'Customer Sale Receipt',
                        'amount' => (float) $sale->total_amount,
                        'type' => 'Revenue',
                    ];
                }
                foreach ($purchaseOrders as $po) {
                    $items[] = [
                        'category' => 'Cost of Goods Sold',
                        'date' => $po->po_date ? (is_string($po->po_date) ? $po->po_date : $po->po_date->format('Y-m-d')) : '-',
                        'doc_no' => $po->po_number ?? ('PO-' . $po->id),
                        'description' => 'Vendor Purchase Order',
                        'amount' => (float) $po->total_amount,
                        'type' => 'COGS',
                    ];
                }
                foreach ($expenses as $exp) {
                    $items[] = [
                        'category' => 'Operating Expense',
                        'date' => $exp->expense_date ? (is_string($exp->expense_date) ? $exp->expense_date : $exp->expense_date->format('Y-m-d')) : '-',
                        'doc_no' => $exp->expense_number ?? ('EXP-' . $exp->id),
                        'description' => $exp->title ?? $exp->description ?? 'Branch Operational Expense',
                        'amount' => (float) $exp->amount,
                        'type' => 'Expense',
                    ];
                }
                foreach ($journalEntries as $je) {
                    $items[] = [
                        'category' => 'Journal Adjustment',
                        'date' => $je->journal && $je->journal->journal_date ? (is_string($je->journal->journal_date) ? $je->journal->journal_date : $je->journal->journal_date->format('Y-m-d')) : '-',
                        'doc_no' => $je->entry_number ?? ('JE-' . $je->id),
                        'description' => $je->description ?? ($je->chartOfAccount->name ?? 'Journal Entry'),
                        'amount' => (float) ($je->debit ?: $je->credit),
                        'type' => $je->debit > 0 ? 'Expense' : 'Revenue',
                    ];
                }
                break;

            case 'Balance Sheet':
                $refDate = $asOfDate ?? $toDate;

                $assetQuery = \App\Models\Asset::query();
                if ($refDate) {
                    $assetQuery->whereDate('created_at', '<=', $refDate);
                }
                $fixedAssetsCost = (float) $assetQuery->sum('purchase_cost');

                $inventoryValue = 0;
                if (\Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Stock)->getTable(), 'avg_cost')) {
                    $inventoryValue = (float) \App\Models\Stock::sum(\DB::raw('quantity * avg_cost'));
                } else {
                    $inventoryValue = (float) \App\Models\Stock::sum('quantity');
                }

                $cashSales = (float) \App\Models\PosSale::when($refDate, fn($q) => $q->whereDate('sale_date', '<=', $refDate))->sum('total_amount');
                $purchaseOutflows = (float) \App\Models\PurchaseOrder::when($refDate, fn($q) => $q->whereDate('po_date', '<=', $refDate))->sum('total_amount');
                $expenseOutflows = (float) \App\Models\BranchExpense::when($refDate, fn($q) => $q->whereDate('expense_date', '<=', $refDate))->sum('amount');

                $cashAndBankBalance = max(0, $cashSales - $purchaseOutflows - $expenseOutflows);
                $totalAssets = $fixedAssetsCost + $inventoryValue + $cashAndBankBalance;

                $accountsPayable = (float) \App\Models\PurchaseOrder::whereIn('status', ['pending', 'approved'])->when($refDate, fn($q) => $q->whereDate('po_date', '<=', $refDate))->sum('total_amount');
                $taxPayable = (float) \App\Models\PosSale::when($refDate, fn($q) => $q->whereDate('sale_date', '<=', $refDate))->sum('tax_amount');
                $totalLiabilities = $accountsPayable + $taxPayable;

                $totalEquity = max(0, $totalAssets - $totalLiabilities);

                $summary = [
                    'total_assets' => $totalAssets,
                    'total_liabilities' => $totalLiabilities,
                    'total_equity' => $totalEquity,
                    'fixed_assets' => $fixedAssetsCost,
                    'inventory_valuation' => $inventoryValue,
                    'cash_bank_balance' => $cashAndBankBalance,
                    'accounts_payable' => $accountsPayable,
                ];

                $items[] = ['category' => 'Current Asset', 'date' => $refDate, 'doc_no' => 'CASH-BANK', 'description' => 'Cash & Bank Liquid Reserves', 'amount' => $cashAndBankBalance, 'type' => 'Asset'];
                $items[] = ['category' => 'Current Asset', 'date' => $refDate, 'doc_no' => 'INV-VAL', 'description' => 'Current Stock Inventory Valuation', 'amount' => $inventoryValue, 'type' => 'Asset'];
                $items[] = ['category' => 'Fixed Asset', 'date' => $refDate, 'doc_no' => 'EQUIP-COST', 'description' => 'Property, Plant & Equipment Valuation', 'amount' => $fixedAssetsCost, 'type' => 'Asset'];
                $items[] = ['category' => 'Liability', 'date' => $refDate, 'doc_no' => 'AP-VENDOR', 'description' => 'Accounts Payable (Vendor Unpaid Orders)', 'amount' => $accountsPayable, 'type' => 'Liability'];
                $items[] = ['category' => 'Liability', 'date' => $refDate, 'doc_no' => 'TAX-LIAB', 'description' => 'Sales & GST Tax Payable', 'amount' => $taxPayable, 'type' => 'Liability'];
                $items[] = ['category' => 'Equity', 'date' => $refDate, 'doc_no' => 'RET-EARN', 'description' => 'Accumulated Retained Earnings & Equity', 'amount' => $totalEquity, 'type' => 'Equity'];
                break;

            case 'Cash Flow':
                $salesInflows = \App\Models\PosSale::query();
                if ($fromDate && $toDate) {
                    $salesInflows->whereBetween('sale_date', [$fromDate, $toDate]);
                }
                $totalInflow = (float) $salesInflows->sum('total_amount');

                $poOutflows = \App\Models\PurchaseOrder::query();
                if ($fromDate && $toDate) {
                    $poOutflows->whereBetween('po_date', [$fromDate, $toDate]);
                }
                $poOutflowTotal = (float) $poOutflows->sum('total_amount');

                $expOutflows = \App\Models\BranchExpense::query();
                if ($fromDate && $toDate) {
                    $expOutflows->whereBetween('expense_date', [$fromDate, $toDate]);
                }
                $expOutflowTotal = (float) $expOutflows->sum('amount');

                $totalOutflow = $poOutflowTotal + $expOutflowTotal;
                $netCashFlow = $totalInflow - $totalOutflow;

                $summary = [
                    'total_inflow' => $totalInflow,
                    'total_outflow' => $totalOutflow,
                    'net_cash_flow' => $netCashFlow,
                ];

                foreach ($salesInflows->get() as $s) {
                    $items[] = [
                        'category' => 'Operating Inflow',
                        'date' => $s->sale_date ? (is_string($s->sale_date) ? $s->sale_date : $s->sale_date->format('Y-m-d')) : '-',
                        'doc_no' => $s->invoice_number,
                        'description' => 'POS Sale Customer Receipt',
                        'amount' => (float) $s->total_amount,
                        'type' => 'Inflow',
                    ];
                }
                foreach ($poOutflows->get() as $p) {
                    $items[] = [
                        'category' => 'Operating Outflow',
                        'date' => $p->po_date ? (is_string($p->po_date) ? $p->po_date : $p->po_date->format('Y-m-d')) : '-',
                        'doc_no' => $p->po_number ?? ('PO-' . $p->id),
                        'description' => 'Purchase Order Cash Outflow',
                        'amount' => (float) $p->total_amount,
                        'type' => 'Outflow',
                    ];
                }
                foreach ($expOutflows->get() as $e) {
                    $items[] = [
                        'category' => 'Operating Outflow',
                        'date' => $e->expense_date ? (is_string($e->expense_date) ? $e->expense_date : $e->expense_date->format('Y-m-d')) : '-',
                        'doc_no' => $e->expense_number ?? ('EXP-' . $e->id),
                        'description' => $e->title ?? $e->description ?? 'Branch Operational Expense',
                        'amount' => (float) $e->amount,
                        'type' => 'Outflow',
                    ];
                }
                break;

            case 'Trial Balance':
            default:
                $accounts = \App\Models\ChartOfAccount::all();
                $totalDebit = 0;
                $totalCredit = 0;

                if ($accounts->isNotEmpty()) {
                    foreach ($accounts as $acc) {
                        $entries = \App\Models\JournalEntry::where('chart_of_account_id', $acc->id)
                            ->whereHas('journal', function ($q) use ($fromDate, $toDate) {
                                if ($fromDate && $toDate) {
                                    $q->whereBetween('journal_date', [$fromDate, $toDate]);
                                }
                            })->get();
                        $d = (float) $entries->sum('debit');
                        $c = (float) $entries->sum('credit');
                        $totalDebit += $d;
                        $totalCredit += $c;

                        $items[] = [
                            'account_code' => $acc->account_code ?? ('ACC-' . $acc->id),
                            'account_name' => $acc->name,
                            'account_type' => $acc->account_type ?? 'General',
                            'debit' => $d,
                            'credit' => $c,
                        ];
                    }
                }

                if (empty($items)) {
                    $salesTot = (float) \App\Models\PosSale::when($fromDate && $toDate, fn($q) => $q->whereBetween('sale_date', [$fromDate, $toDate]))->sum('total_amount');
                    $poTot = (float) \App\Models\PurchaseOrder::when($fromDate && $toDate, fn($q) => $q->whereBetween('po_date', [$fromDate, $toDate]))->sum('total_amount');
                    $expTot = (float) \App\Models\BranchExpense::when($fromDate && $toDate, fn($q) => $q->whereBetween('expense_date', [$fromDate, $toDate]))->sum('amount');

                    $items[] = ['account_code' => '4001', 'account_name' => 'Sales Revenue Account', 'account_type' => 'Revenue', 'debit' => 0.00, 'credit' => $salesTot];
                    $items[] = ['account_code' => '5001', 'account_name' => 'Cost of Goods Sold / Purchases', 'account_type' => 'Expense', 'debit' => $poTot, 'credit' => 0.00];
                    $items[] = ['account_code' => '6001', 'account_name' => 'Branch Operating Expenses', 'account_type' => 'Expense', 'debit' => $expTot, 'credit' => 0.00];
                    $items[] = ['account_code' => '1001', 'account_name' => 'Cash & Bank Liquid Account', 'account_type' => 'Asset', 'debit' => max(0, $salesTot - $poTot - $expTot), 'credit' => 0.00];

                    $totalDebit = $poTot + $expTot + max(0, $salesTot - $poTot - $expTot);
                    $totalCredit = $salesTot;
                }

                $summary = [
                    'total_debit' => $totalDebit,
                    'total_credit' => $totalCredit,
                    'difference' => abs($totalDebit - $totalCredit),
                    'is_balanced' => abs($totalDebit - $totalCredit) < 0.01,
                ];
                break;
        }

        return [
            'report_type' => $reportType,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'as_of_date' => $asOfDate,
            'summary' => $summary,
            'items' => $items,
        ];
    }

    public static function downloadFinancialReportPdf(object $record): StreamedResponse
    {
        $fromDate = $record->from_date ? ($record->from_date instanceof \DateTimeInterface ? $record->from_date->format('Y-m-d') : (string)$record->from_date) : null;
        $toDate = $record->to_date ? ($record->to_date instanceof \DateTimeInterface ? $record->to_date->format('Y-m-d') : (string)$record->to_date) : null;
        $asOfDate = $record->as_of_date ? ($record->as_of_date instanceof \DateTimeInterface ? $record->as_of_date->format('Y-m-d') : (string)$record->as_of_date) : null;
        $reportType = $record->report_type ?? 'Profit & Loss';

        $data = static::getFinancialReportData($reportType, $fromDate, $toDate, $asOfDate);

        $pdf = Pdf::loadView('reports.financial_report_pdf', [
            'record' => $record,
            'reportType' => $data['report_type'],
            'summary' => $data['summary'],
            'items' => $data['items'],
            'fromDate' => $data['from_date'],
            'toDate' => $data['to_date'],
            'asOfDate' => $data['as_of_date'],
        ]);

        $filename = 'financial-report-' . \Illuminate\Support\Str::slug($reportType) . '-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function downloadHrReportPdf(object $record): StreamedResponse
    {
        $fromDate = $record->from_date ? ($record->from_date instanceof \DateTimeInterface ? $record->from_date->format('Y-m-d') : (string)$record->from_date) : null;
        $toDate = $record->to_date ? ($record->to_date instanceof \DateTimeInterface ? $record->to_date->format('Y-m-d') : (string)$record->to_date) : null;
        $date = $record->report_month ? ($record->report_month instanceof \DateTimeInterface ? $record->report_month->format('Y-m-d') : (string)$record->report_month) : null;
        $branchId = $record->branch_id ?? null;

        // 1. New Hires Query
        $newHiresQuery = \App\Models\Employee::with(['department', 'designation']);
        if ($branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Employee)->getTable(), 'branch_id')) {
            $newHiresQuery->where('branch_id', $branchId);
        }
        if ($fromDate && $toDate) {
            $newHiresQuery->whereBetween('joining_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $newHiresQuery->whereDate('joining_date', '>=', $fromDate);
        } elseif ($toDate) {
            $newHiresQuery->whereDate('joining_date', '<=', $toDate);
        } elseif ($date) {
            if (strlen($date) === 7) {
                $newHiresQuery->whereYear('joining_date', substr($date, 0, 4))
                    ->whereMonth('joining_date', substr($date, 5, 2));
            } else {
                $newHiresQuery->whereDate('joining_date', $date);
            }
        }
        $newHires = $newHiresQuery->get();

        // 2. Exits Query
        $exitsQuery = \App\Models\EmployeeExit::with(['employee']);
        if ($fromDate && $toDate) {
            $exitsQuery->where(function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('resignation_date', [$fromDate, $toDate])
                  ->orWhereBetween('last_working_day', [$fromDate, $toDate]);
            });
        } elseif ($fromDate) {
            $exitsQuery->where(function ($q) use ($fromDate) {
                $q->whereDate('resignation_date', '>=', $fromDate)
                  ->orWhereDate('last_working_day', '>=', $fromDate);
            });
        } elseif ($toDate) {
            $exitsQuery->where(function ($q) use ($toDate) {
                $q->whereDate('resignation_date', '<=', $toDate)
                  ->orWhereDate('last_working_day', '<=', $toDate);
            });
        } elseif ($date) {
            if (strlen($date) === 7) {
                $exitsQuery->where(function ($q) use ($date) {
                    $q->whereYear('resignation_date', substr($date, 0, 4))
                      ->whereMonth('resignation_date', substr($date, 5, 2))
                      ->orWhere(function ($q2) use ($date) {
                          $q2->whereYear('last_working_day', substr($date, 0, 4))
                             ->whereMonth('last_working_day', substr($date, 5, 2));
                      });
                });
            } else {
                $exitsQuery->where(function ($q) use ($date) {
                    $q->whereDate('resignation_date', $date)
                      ->orWhereDate('last_working_day', $date);
                });
            }
        }
        $exits = $exitsQuery->get();

        // 3. Leaves Query
        $leavesQuery = \App\Models\EmployeeLeave::with(['employee', 'leaveType']);
        if ($fromDate && $toDate) {
            $leavesQuery->where(function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('start_date', [$fromDate, $toDate])
                  ->orWhereBetween('end_date', [$fromDate, $toDate]);
            });
        } elseif ($fromDate) {
            $leavesQuery->whereDate('start_date', '>=', $fromDate);
        } elseif ($toDate) {
            $leavesQuery->whereDate('end_date', '<=', $toDate);
        } elseif ($date) {
            if (strlen($date) === 7) {
                $leavesQuery->whereYear('start_date', substr($date, 0, 4))
                    ->whereMonth('start_date', substr($date, 5, 2));
            } else {
                $leavesQuery->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
            }
        }
        $leaves = $leavesQuery->get();

        // 4. Attendance Records Query
        $attendanceQuery = \App\Models\Attendance::with(['employee']);
        if ($fromDate && $toDate) {
            $attendanceQuery->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $attendanceQuery->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $attendanceQuery->whereDate('date', '<=', $toDate);
        } elseif ($date) {
            if (strlen($date) === 7) {
                $attendanceQuery->whereYear('date', substr($date, 0, 4))
                    ->whereMonth('date', substr($date, 5, 2));
            } else {
                $attendanceQuery->whereDate('date', $date);
            }
        }
        if ($branchId && \Illuminate\Support\Facades\Schema::hasColumn((new \App\Models\Attendance)->getTable(), 'branch_id')) {
            $attendanceQuery->where('branch_id', $branchId);
        }
        $attendanceRecords = $attendanceQuery->orderBy('date', 'desc')->get();

        $pdf = Pdf::loadView('reports.hr_report_pdf', [
            'record' => $record,
            'newHires' => $newHires,
            'exits' => $exits,
            'leaves' => $leaves,
            'attendanceRecords' => $attendanceRecords,
        ]);

        $filename = 'hr-report-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function downloadBranchPerformanceReportPdf(object $record): StreamedResponse
    {
        $fromDate = $record->from_date ? ($record->from_date instanceof \DateTimeInterface ? $record->from_date->format('Y-m-d') : (string)$record->from_date) : null;
        $toDate = $record->to_date ? ($record->to_date instanceof \DateTimeInterface ? $record->to_date->format('Y-m-d') : (string)$record->to_date) : null;
        $date = $record->report_month ? ($record->report_month instanceof \DateTimeInterface ? $record->report_month->format('Y-m-d') : (string)$record->report_month) : null;
        $branchId = $record->branch_id ?? null;

        $query = \App\Models\PosSale::query();
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('sale_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('sale_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('sale_date', '<=', $toDate);
        } elseif ($date) {
            if (strlen($date) === 7) {
                $query->whereYear('sale_date', substr($date, 0, 4))
                    ->whereMonth('sale_date', substr($date, 5, 2));
            } else {
                $query->whereDate('sale_date', $date);
            }
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        $pdf = Pdf::loadView('reports.branch_performance_report_pdf', [
            'record' => $record,
            'sales' => $sales,
        ]);

        $filename = 'branch-performance-report-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public static function downloadKpiReportPdf(object $record): StreamedResponse
    {
        $fromDate = $record->from_date ? ($record->from_date instanceof \DateTimeInterface ? $record->from_date->format('Y-m-d') : (string)$record->from_date) : null;
        $toDate = $record->to_date ? ($record->to_date instanceof \DateTimeInterface ? $record->to_date->format('Y-m-d') : (string)$record->to_date) : null;
        $date = $record->report_date ? ($record->report_date instanceof \DateTimeInterface ? $record->report_date->format('Y-m-d') : (string)$record->report_date) : null;

        $empCount = \App\Models\Employee::count();

        $salesQuery = \App\Models\PosSale::query();
        if ($fromDate && $toDate) {
            $salesQuery->whereBetween('sale_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $salesQuery->whereDate('sale_date', '>=', $fromDate);
        } elseif ($toDate) {
            $salesQuery->whereDate('sale_date', '<=', $toDate);
        } elseif ($date) {
            $salesQuery->whereDate('sale_date', $date);
        }
        $totalSales = (float) $salesQuery->sum('net_amount');

        $attendanceQuery = \App\Models\Attendance::query();
        if ($fromDate && $toDate) {
            $attendanceQuery->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $attendanceQuery->whereDate('date', '>=', $fromDate);
        } elseif ($toDate) {
            $attendanceQuery->whereDate('date', '<=', $toDate);
        }
        $totalAttendance = $attendanceQuery->count();

        $pdf = Pdf::loadView('reports.kpi_report_pdf', [
            'record' => $record,
            'empCount' => $empCount,
            'totalSales' => $totalSales,
            'totalAttendance' => $totalAttendance,
        ]);

        $filename = 'kpi-report-' . ($record->id ?? time()) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }
    public static function downloadPosSaleReceiptPdf(object $record): StreamedResponse
    {
        $pdf = Pdf::loadView('reports.pos_sale_receipt_pdf', ['record' => $record]);
        $filename = 'POS-Receipt-' . ($record->invoice_number ?? $record->id) . '-' . now()->format('YmdHis') . '.pdf';
        return response()->streamDownload(fn () => print($pdf->output()), $filename);
    }
}

