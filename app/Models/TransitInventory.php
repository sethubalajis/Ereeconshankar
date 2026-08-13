<?php

namespace App\Models;

use App\Traits\HasTransferBranch;

use Illuminate\Database\Eloquent\Model;

class TransitInventory extends Model
{
    use HasTransferBranch;

    protected $guarded = [];

    //
    public function stockTransfer() {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
