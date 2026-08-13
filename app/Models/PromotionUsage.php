<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class PromotionUsage extends Model
{
    use HasBranch;

    protected $guarded = [];

    public function posSale() {
        return $this->belongsTo(PosSale::class);
    }
    public function posSaleItem() {
        return $this->belongsTo(PosSaleItem::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    public function branch() {
        return $this->belongsTo(Branch::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
