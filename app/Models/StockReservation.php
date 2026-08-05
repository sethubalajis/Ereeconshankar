<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::observe(\App\Observers\StockReservationObserver::class);
    }

    const STATUS_PENDING = 'Pending';
    const STATUS_RESERVED = 'Reserved';
    const STATUS_RELEASED = 'Released';
    const STATUS_EXPIRED = 'Expired';
    const STATUS_COMPLETED = 'Completed';

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query) {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_RESERVED]);
    }
}
