<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class UserBranchAccess extends Model
{
    use HasBranch;


    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    //
}
