<?php

namespace App\Traits;

use App\Models\FinancialYear;
use Illuminate\Database\Eloquent\Builder;
use Exception;

trait BelongsToFinancialYear
{
    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }

    protected static function bootBelongsToFinancialYear()
    {
        static::creating(function ($model) {
            if (empty($model->financial_year_id)) {
                // Try to find the active financial year
                $activeYear = FinancialYear::where('is_active', true)->first();
                if ($activeYear) {
                    $model->financial_year_id = $activeYear->id;
                }
            }
        });

        static::updating(function ($model) {
            if ($model->financialYear && $model->financialYear->is_closed) {
                throw new Exception("Cannot modify a transaction in a closed financial year.");
            }
        });

        static::deleting(function ($model) {
            if ($model->financialYear && $model->financialYear->is_closed) {
                throw new Exception("Cannot delete a transaction in a closed financial year.");
            }
        });
    }
}
