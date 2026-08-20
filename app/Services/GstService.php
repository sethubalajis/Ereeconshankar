<?php

namespace App\Services;

use App\Models\TaxInvoice;
use App\Models\GstRate;
use Exception;
use Illuminate\Database\Eloquent\Model;

class GstService
{
    /**
     * Calculate GST details based on taxable value and GST Rate.
     * Assumes intra-state (CGST + SGST) by default.
     */
    public function calculateGst(float $taxableValue, float $ratePercentage, bool $isInterState = false)
    {
        $totalGst = round($taxableValue * ($ratePercentage / 100), 2);
        
        if ($isInterState) {
            return [
                'cgst' => 0.0,
                'sgst' => 0.0,
                'igst' => $totalGst,
                'total_gst' => $totalGst,
            ];
        } else {
            $halfGst = round($totalGst / 2, 2);
            return [
                'cgst' => $halfGst,
                'sgst' => $totalGst - $halfGst, // to handle odd rounding if any
                'igst' => 0.0,
                'total_gst' => $totalGst,
            ];
        }
    }

    /**
     * Generate Tax Invoice from a Transaction Model (e.g. PosSale, Grn)
     */
    public function generateTaxInvoice(Model $transaction, array $invoiceData)
    {
        $invoiceData['transactionable_id'] = $transaction->id;
        $invoiceData['transactionable_type'] = get_class($transaction);
        
        return TaxInvoice::create($invoiceData);
    }
}
