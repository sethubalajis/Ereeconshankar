<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $guarded = [];

    //
    public function stock() {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    protected static function booted()
    {
        static::created(function ($model) {
            // StockLedger creation is now handled centrally in App\Services\InventoryService
        });
    }
}
