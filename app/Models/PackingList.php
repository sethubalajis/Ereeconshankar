<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class PackingList extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function pickingList() {
        return $this->belongsTo(PickingList::class, 'picking_list_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
