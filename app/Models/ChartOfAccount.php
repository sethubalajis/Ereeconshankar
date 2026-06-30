<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $guarded = [];

    //
    public function accountGroup() {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }
}
