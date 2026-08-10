<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\PosSale;
use App\Models\CustomerWallet;
use Exception;

class WalletService
{
    /**
     * Debit the wallet for a POS sale.
     *
     * @param Customer $customer
     * @param PosSale $posSale
     * @param float $amount
     * @throws Exception
     */
    public function debitWallet(Customer $customer, PosSale $posSale, float $amount)
    {
        if ($amount <= 0) return;

        // Check if sufficient balance
        if ($customer->wallet_balance < $amount) {
            throw new Exception("Insufficient wallet balance. Available: ₹{$customer->wallet_balance}, Requested: ₹{$amount}");
        }

        // Check if wallet exists and is active (Assuming there's a master wallet record, or we just rely on Customer balance)
        // If CustomerWallet is the transaction model:
        CustomerWallet::create([
            'customer_id' => $customer->id,
            // 'wallet_id' => $walletId, // If there's a specific wallet, but often tied to customer
            'pos_sale_id' => $posSale->id,
            'branch_id' => $posSale->branch_id,
            'transaction_type' => 'debit',
            'amount' => $amount,
            'reference' => 'Debited for POS Sale #' . $posSale->id,
            'created_by' => auth()->id() ?? 1,
        ]);

        $customer->decrement('wallet_balance', $amount);
    }
    
    /**
     * Credit the wallet (for returns or direct topup).
     *
     * @param Customer $customer
     * @param PosSale $posSale
     * @param float $amount
     * @throws Exception
     */
    public function creditWallet(Customer $customer, PosSale $posSale, float $amount)
    {
        if ($amount <= 0) return;

        CustomerWallet::create([
            'customer_id' => $customer->id,
            'pos_sale_id' => $posSale->id,
            'branch_id' => $posSale->branch_id,
            'transaction_type' => 'credit',
            'amount' => $amount,
            'reference' => 'Credited from POS Sale #' . $posSale->id,
            'created_by' => auth()->id() ?? 1,
        ]);

        $customer->increment('wallet_balance', $amount);
    }
}
