<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\GstRate;

class PricingEngine
{
    /**
     * Get the base selling price for a product/variant.
     */
    public function getBasePrice(Product $product, ?ProductVariant $variant = null): float
    {
        if ($variant && $variant->price > 0) {
            return (float) $variant->price;
        }
        
        return (float) $product->selling_price;
    }

    /**
     * Calculate GST for a given taxable amount.
     * Returns an array with cgst, sgst, igst, and gst_amount.
     */
    public function calculateGst(float $taxableAmount, Product $product, string $branchState = '', string $customerState = ''): array
    {
        $gstData = [
            'cgst' => 0.0,
            'sgst' => 0.0,
            'igst' => 0.0,
            'gst_amount' => 0.0,
        ];

        // Fetch GST Rate from product's HSN or Tax Group
        $gstRate = $this->getGstRateForProduct($product);
        if (!$gstRate) {
            return $gstData;
        }

        $totalRate = (float) $gstRate->rate;
        $isInterState = (!empty($branchState) && !empty($customerState) && $branchState !== $customerState);

        if ($isInterState) {
            $gstData['igst'] = ($taxableAmount * $totalRate) / 100;
            $gstData['gst_amount'] = $gstData['igst'];
        } else {
            $halfRate = $totalRate / 2;
            $gstData['cgst'] = ($taxableAmount * $halfRate) / 100;
            $gstData['sgst'] = ($taxableAmount * $halfRate) / 100;
            $gstData['gst_amount'] = $gstData['cgst'] + $gstData['sgst'];
        }

        // Round to 2 decimal places
        $gstData['cgst'] = round($gstData['cgst'], 2);
        $gstData['sgst'] = round($gstData['sgst'], 2);
        $gstData['igst'] = round($gstData['igst'], 2);
        $gstData['gst_amount'] = round($gstData['gst_amount'], 2);

        return $gstData;
    }

    /**
     * Helper to resolve the applicable GST rate for a product.
     */
    protected function getGstRateForProduct(Product $product): ?GstRate
    {
        // Fallback hierarchy: HSN Code -> Tax Group
        if ($product->hsnSacCode && $product->hsnSacCode->gstRate) {
            return $product->hsnSacCode->gstRate;
        }
        
        if ($product->taxGroup && $product->taxGroup->gstRates()->exists()) {
            // For simplicity, take the first valid rate in the group
            return $product->taxGroup->gstRates()->first();
        }

        return null;
    }
}
