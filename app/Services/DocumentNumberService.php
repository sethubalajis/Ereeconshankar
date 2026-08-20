<?php

namespace App\Services;

use App\Models\DocumentNumberConfiguration;
use App\Models\DocumentNumberSequence;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\DB;
use Exception;

class DocumentNumberService
{
    /**
     * Generate the next document number based on configuration.
     *
     * @param string $documentType
     * @param int $companyId
     * @param int|null $branchId
     * @param string|null $overrideDate
     * @return string
     * @throws Exception
     */
    public static function generate(
        string $documentType,
        int $companyId,
        ?int $branchId = null,
        ?string $overrideDate = null
    ): string {
        $config = DocumentNumberConfiguration::where('company_id', $companyId)
            ->where('document_type', $documentType)
            ->where('is_active', true)
            ->first();

        // If no config found, fallback to a default system format
        if (!$config) {
            $prefix = strtoupper(substr(class_basename($documentType), 0, 3));
            $seq = self::getNextSequence($companyId, $branchId, $documentType, null, 1);
            return $prefix . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
        }

        $financialYearString = null;
        if ($config->include_financial_year) {
            $date = $overrideDate ? \Carbon\Carbon::parse($overrideDate) : now();
            // Try to find the active financial year for this company based on the date
            $fy = FinancialYear::where('company_id', $companyId)
                ->where('start_date', '<=', $date->format('Y-m-d'))
                ->where('end_date', '>=', $date->format('Y-m-d'))
                ->first();
                
            if ($fy) {
                // Determine format
                $format = $config->financial_year_format ?? 'YYYY-YY';
                $startYear = \Carbon\Carbon::parse($fy->start_date)->format('Y');
                $endYear = \Carbon\Carbon::parse($fy->end_date)->format('Y');
                
                if ($format === 'YYYY-YYYY') {
                    $financialYearString = $startYear . '-' . $endYear;
                } elseif ($format === 'YY-YY') {
                    $financialYearString = substr($startYear, 2) . '-' . substr($endYear, 2);
                } else { // YYYY-YY
                    $financialYearString = $startYear . '-' . substr($endYear, 2);
                }
            } else {
                // Fallback if no financial year found for the date
                $financialYearString = $date->format('Y');
            }
        }

        $branchScopeId = $config->branch_wise ? $branchId : null;
        
        $seqFinancialYear = null;
        if ($config->reset_frequency === 'yearly' && $financialYearString) {
            $seqFinancialYear = $financialYearString;
        } elseif ($config->reset_frequency === 'monthly') {
            $date = $overrideDate ? \Carbon\Carbon::parse($overrideDate) : now();
            $seqFinancialYear = $date->format('Y-m');
        }

        $sequenceNumber = self::getNextSequence(
            $companyId,
            $branchScopeId,
            $documentType,
            $seqFinancialYear,
            $config->starting_number
        );

        $number = str_pad((string)$sequenceNumber, $config->number_padding, '0', STR_PAD_LEFT);
        $separator = $config->separator ?? '';

        $parts = [];
        if (!empty($config->prefix)) {
            $parts[] = $config->prefix;
        }
        if ($config->include_financial_year && $financialYearString) {
            $parts[] = $financialYearString;
        }
        $parts[] = $number;
        if (!empty($config->suffix)) {
            $parts[] = $config->suffix;
        }

        return implode($separator, $parts);
    }

    /**
     * Safely get and increment the sequence with pessimistic locking.
     * 
     * @return int
     */
    private static function getNextSequence(
        int $companyId,
        ?int $branchId,
        string $documentType,
        ?string $financialYear,
        int $startingNumber
    ): int {
        return DB::transaction(function () use (
            $companyId,
            $branchId,
            $documentType,
            $financialYear,
            $startingNumber
        ) {
            $sequence = DocumentNumberSequence::where('company_id', $companyId)
                ->where('branch_id', $branchId)
                ->where('document_type', $documentType)
                ->where('financial_year', $financialYear)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = DocumentNumberSequence::create([
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'document_type' => $documentType,
                    'financial_year' => $financialYear,
                    'current_number' => $startingNumber,
                ]);
                return $startingNumber;
            }

            $sequence->current_number += 1;
            $sequence->save();

            return $sequence->current_number;
        });
    }
}
