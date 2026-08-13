<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function stock() {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::created(function ($model) {
            // StockLedger creation is now handled centrally in App\Services\InventoryService
        });
    }
}
