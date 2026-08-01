<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            // Ensure only one active financial year per company
            if ($model->is_active) {
                $query = static::where('is_active', true);
                if ($model->company_id) {
                    $query->where('company_id', $model->company_id);
                } else {
                    $query->whereNull('company_id');
                }

                if ($model->exists) {
                    $query->where('id', '!=', $model->id);
                }

                $query->update(['is_active' => false]);
            }
        });
    }
}
