<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class VendorPerformance extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
