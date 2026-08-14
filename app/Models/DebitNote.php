<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class DebitNote extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function purchaseReturn() {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
