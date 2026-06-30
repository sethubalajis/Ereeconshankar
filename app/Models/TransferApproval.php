<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferApproval extends Model
{
    protected $guarded = [];

    //
    public function transferRequest() {
        return $this->belongsTo(TransferRequest::class, 'transfer_request_id');
    }
    public function approver() {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
