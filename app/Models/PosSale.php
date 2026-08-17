<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class PosSale extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::observe(\App\Observers\PosSaleObserver::class);
    }

    protected $casts = [
        'payments' => 'array',
        'applied_offers' => 'array',
    ];

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function posTerminal() {
        return $this->belongsTo(PosTerminal::class, 'pos_terminal_id');
    }
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function giftVoucher() {
        return $this->belongsTo(GiftVoucher::class, 'gift_voucher_id');
    }
    public function posSaleItems() {
        return $this->hasMany(PosSaleItem::class, 'pos_sale_id');
    }
    public function stockReservation() {
        return $this->belongsTo(StockReservation::class, 'stock_reservation_id');
    }

    public function customerMembership() {
        return $this->belongsTo(CustomerMembership::class, 'customer_membership_id');
    }
}
