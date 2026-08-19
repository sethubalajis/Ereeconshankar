<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class TransferReceipt extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function stockTransfer() {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function receivedBy() {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function transferItems()
    {
        // A receipt is for a stock transfer, which has items. 
        // We can link them by stock_transfer_id.
        return $this->hasMany(TransferItem::class, 'stock_transfer_id', 'stock_transfer_id');
    }
}
