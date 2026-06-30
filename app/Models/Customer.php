<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    //
    public function customerGroup() {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }
}
