<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
    protected $guarded = [];

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    protected static function booted()
    {
        $recalculate = function ($wallet) {
            if ($wallet->customer_id) {
                $customer = Customer::find($wallet->customer_id);
                if ($customer) {
                    $credits = static::where('customer_id', $wallet->customer_id)
                        ->where('transaction_type', 'credit')
                        ->sum('amount');
                        
                    $debits = static::where('customer_id', $wallet->customer_id)
                        ->where('transaction_type', 'debit')
                        ->sum('amount');
                        
                    $customer->update(['wallet_balance' => $credits - $debits]);
                }
            }
        };

        static::saved($recalculate);
        static::deleted($recalculate);
    }
}
