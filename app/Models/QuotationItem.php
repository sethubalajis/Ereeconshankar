<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use \App\Traits\HasBranch;

    protected $guarded = [];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
