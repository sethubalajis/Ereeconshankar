<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandisePlanning extends Model
{
    protected $guarded = [];

    //
    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }
    public function season() {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
