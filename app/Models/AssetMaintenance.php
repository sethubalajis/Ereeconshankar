<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function asset() {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
