<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/reports/sales/{report}/pdf', [\App\Http\Controllers\ReportPdfController::class, 'downloadSalesReport'])->name('reports.sales.pdf');
});
