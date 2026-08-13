<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class AssetAllocation extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function asset() {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
