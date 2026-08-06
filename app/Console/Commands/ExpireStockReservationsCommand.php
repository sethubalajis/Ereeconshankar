<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireStockReservationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire stock reservations that have passed their expiry date';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\StockReservationService $service)
    {
        $expiredReservations = \App\Models\StockReservation::whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->active()
            ->get();

        $count = 0;
        foreach ($expiredReservations as $reservation) {
            try {
                $service->expireReservation($reservation);
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to expire reservation #{$reservation->reservation_number}: " . $e->getMessage());
            }
        }

        $this->info("Expired {$count} reservations successfully.");
    }
}
