<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchandisePlanning extends Model
{
    use HasBranch;

    protected $guarded = [];

    public function financialYear(): BelongsTo
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MerchandisePlanningItem::class, 'merchandise_planning_id');
    }
}
