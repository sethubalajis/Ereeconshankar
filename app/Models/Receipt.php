<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function posSale() {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
