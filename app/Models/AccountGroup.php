<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $guarded = [];

    //
    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
