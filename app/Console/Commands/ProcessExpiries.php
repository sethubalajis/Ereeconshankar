<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessExpiries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erp:process-expiries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all automatic status expiries across the ERP';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\AutomaticStatusExpiryService $service)
    {
        $this->info('Starting automatic status expiry process...');

        // Register handlers
        $service->registerHandler(new \App\Services\ExpiryHandlers\StockReservationHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\CouponHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\CustomerMembershipHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\LoyaltyCardHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\QuotationHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\RfqHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\PriceListHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\BuyXGetYOfferHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\ComboOfferHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\DiscountRuleHandler());
        $service->registerHandler(new \App\Services\ExpiryHandlers\FestivalOfferHandler());

        // Process expiries
        $service->processExpiries();

        $this->info('Automatic status expiry process completed.');
    }
}
