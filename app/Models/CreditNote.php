<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
