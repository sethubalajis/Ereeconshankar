<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function assetCategory() {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
