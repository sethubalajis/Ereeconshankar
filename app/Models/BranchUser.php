<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{
    protected $guarded = [];

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
