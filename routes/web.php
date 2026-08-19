<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pos-sales/{pos_sale}/receipt', function (\App\Models\PosSale $pos_sale) {
    if (!request()->hasValidSignature()) {
        abort(401, 'This receipt link has expired or is invalid.');
    }
    return \App\Helpers\ReportPdfHelper::downloadPosSaleReceiptPdf($pos_sale);
})->name('pos-sales.receipt.download');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/reports/sales/{report}/pdf', [\App\Http\Controllers\ReportPdfController::class, 'downloadSalesReport'])->name('reports.sales.pdf');
});

Route::get('/transfer-receipts/{transfer_receipt}/pdf', function (\App\Models\TransferReceipt $transfer_receipt) { return \App\Helpers\ReportPdfHelper::downloadTransferReceiptPdf($transfer_receipt); })->name('transfer-receipts.pdf');
Route::get('/stock-transfers/{stock_transfer}/pdf', function (\App\Models\StockTransfer $stock_transfer) { return \App\Helpers\ReportPdfHelper::downloadStockTransferPdf($stock_transfer); })->name('stock-transfers.pdf');
