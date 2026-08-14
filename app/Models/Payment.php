<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function chartOfAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
    public function paymentMode() {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
