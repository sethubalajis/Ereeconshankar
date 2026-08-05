<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamagedProduct extends Model
{
    protected $guarded = [];

    protected $casts = [
        'reported_date' => 'date',
    ];

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

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function source()
    {
        return $this->morphTo();
    }
}
