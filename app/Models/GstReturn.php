<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class GstReturn extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
