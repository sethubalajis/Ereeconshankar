<?php

namespace App\Models;

use App\Traits\HasTransferBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasTransferBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function transferRequest() {
        return $this->belongsTo(TransferRequest::class, 'transfer_request_id');
    }
    public function fromBranch() {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }
    public function toBranch() {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function transferItems() {
        return $this->hasMany(TransferItem::class, 'stock_transfer_id');
    }
}
