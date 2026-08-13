<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class TransferApproval extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function transferRequest() {
        return $this->belongsTo(TransferRequest::class, 'transfer_request_id');
    }
    public function approver() {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
