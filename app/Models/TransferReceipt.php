<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferReceipt extends Model
{
    protected $guarded = [];

    //
    public function stockTransfer() {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function receivedBy() {
        return $this->belongsTo(User::class, 'received_by');
    }
}
