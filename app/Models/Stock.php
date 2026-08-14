<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasBranch;

    protected $guarded = [];



    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function reserve($qty) {
        $this->reserved_qty += $qty;
        $this->available_qty = $this->quantity - $this->reserved_qty - ($this->damaged_qty ?? 0);
        $this->save();
    }

    public function release($qty) {
        $this->reserved_qty -= $qty;
        $this->available_qty = $this->quantity - $this->reserved_qty - ($this->damaged_qty ?? 0);
        $this->save();
    }
}
