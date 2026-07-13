<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSale extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if ($model->customer_id) {
                $customer = Customer::find($model->customer_id);
                if ($customer) {
                    // Deduct redeemed points (assuming 1 point = 1 unit amount for simplicity, or we just record it)
                    if ($model->redeem_points_amount > 0) {
                        $pointsToDeduct = (int) $model->redeem_points_amount;
                        $customer->total_points -= $pointsToDeduct;
                        \Illuminate\Support\Facades\DB::table('loyalty_points')->insert([
                            'customer_id' => $customer->id,
                            'transaction_type' => 'redeem',
                            'points' => $pointsToDeduct,
                            'description' => 'Redeemed on POS Sale ' . $model->invoice_number,
                            'transaction_date' => now()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    
                    // Earn new points (e.g., 1 point per 100 spent)
                    if ($model->net_amount > 0) {
                        $pointsEarned = (int) ($model->net_amount / 100);
                        $customer->total_points += $pointsEarned;
                        \Illuminate\Support\Facades\DB::table('loyalty_points')->insert([
                            'customer_id' => $customer->id,
                            'transaction_type' => 'earn',
                            'points' => $pointsEarned,
                            'description' => 'Earned from POS Sale ' . $model->invoice_number,
                            'transaction_date' => now()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Deduct used wallet balance
                    if ($model->use_wallet_balance > 0) {
                        $customer->wallet_balance -= $model->use_wallet_balance;
                        \Illuminate\Support\Facades\DB::table('customer_wallets')->insert([
                            'customer_id' => $customer->id,
                            'transaction_type' => 'debit',
                            'amount' => $model->use_wallet_balance,
                            'description' => 'Used on POS Sale ' . $model->invoice_number,
                            'transaction_date' => now()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $customer->save();
                }
            }
        });
    }

    protected $casts = [
        'payments' => 'array',
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
    public function posSaleItems() {
        return $this->hasMany(PosSaleItem::class, 'pos_sale_id');
    }
}
