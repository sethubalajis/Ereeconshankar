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
