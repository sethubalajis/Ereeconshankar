<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
