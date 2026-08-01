<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialYear extends Model
{

    protected $guarded = [];

    public function merchandisePlannings(): HasMany
    {
        return $this->hasMany(MerchandisePlanning::class, 'financial_year_id');
    }
}
