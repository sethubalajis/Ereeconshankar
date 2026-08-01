<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class AssetTransfer extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function asset() {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
    public function fromBranch() {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }
    public function toBranch() {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
