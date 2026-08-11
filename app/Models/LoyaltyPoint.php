<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToFinancialYear;

class LoyaltyPoint extends Model
{
    use BelongsToFinancialYear;

    protected $guarded = [];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function posSale() {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
