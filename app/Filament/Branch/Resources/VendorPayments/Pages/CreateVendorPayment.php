<?php

namespace App\Filament\Branch\Resources\VendorPayments\Pages;

use App\Filament\Branch\Resources\VendorPayments\VendorPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorPayment extends CreateRecord
{
    protected static string $resource = VendorPaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
