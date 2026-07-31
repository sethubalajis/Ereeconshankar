<?php

namespace App\Jobs;

use App\Models\PosSale;
use App\Mail\PosSaleInvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPosSaleEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $posSale;

    /**
     * Create a new job instance.
     */
    public function __construct(PosSale $posSale)
    {
        $this->posSale = $posSale;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->posSale->loadMissing(['customer', 'branch.company']);

        $customer = $this->posSale->customer;
        
        if (!$customer || empty($customer->email)) {
            $this->posSale->update(['email_status' => 'failed']);
            return;
        }

        try {
            Mail::to($customer->email)->send(new PosSaleInvoiceMail($this->posSale));
            
            $this->posSale->update([
                'email_status' => 'sent',
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send POS sale email: " . $e->getMessage());
            $this->posSale->update([
                'email_status' => 'failed',
            ]);
        }
    }
}
