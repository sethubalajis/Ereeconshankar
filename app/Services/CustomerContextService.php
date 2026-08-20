<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyProgram;
use Illuminate\Support\Carbon;

class CustomerContextService
{
    /**
     * Identify a customer based on search criteria.
     * Searches by mobile, code, name, or active loyalty card number.
     *
     * @param string $search
     * @return Customer|null
     */
    public function identifyCustomer(string $search): ?Customer
    {
        $now = \Illuminate\Support\Carbon::now();
        // Check for Loyalty Card first
        $loyaltyCard = LoyaltyCard::where('card_number', $search)
            ->where('status', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', $now->startOfDay());
            })
            ->first();

        if ($loyaltyCard && $loyaltyCard->customer) {
            return $loyaltyCard->customer;
        }

        // Search in Customer table
        return Customer::where('mobile', $search)
            ->orWhere('code', $search)
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->first();
    }

    /**
     * Load full customer context for POS.
     *
     * @param Customer $customer
     * @return array
     */
    public function loadContext(Customer $customer): array
    {
        $now = Carbon::now();

        // Load relations
        $customer->loadMissing(['customerGroup', 'loyaltyCards']);

        $group = $customer->customerGroup;
        
        // Load active membership assignment
        $activeMembershipAssignment = $customer->activeMembership()->with('membershipPlan')->first();
        $membership = $activeMembershipAssignment ? $activeMembershipAssignment->membershipPlan : null;

        // Active Loyalty Card
        $activeLoyaltyCard = $customer->loyaltyCards()
            ->where('status', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', $now->startOfDay());
            })
            ->first();

        // Loyalty Program
        $loyaltyProgram = null;
        if (method_exists(LoyaltyProgram::class, 'resolveForCustomer')) {
            $loyaltyProgram = LoyaltyProgram::resolveForCustomer($customer);
        }

        // Wallet Balance
        $walletBalance = (float) $customer->wallet_balance;

        // Loyalty Points
        $availablePoints = (int) $customer->total_points;

        return [
            'customer' => $customer,
            'customer_group' => $group,
            'customer_membership_assignment' => $activeMembershipAssignment,
            'membership_plan' => $membership,
            'loyalty_card' => $activeLoyaltyCard,
            'loyalty_program' => $loyaltyProgram,
            'wallet_balance' => $walletBalance,
            'available_points' => $availablePoints,
        ];
    }
}
