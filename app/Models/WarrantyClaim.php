<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
