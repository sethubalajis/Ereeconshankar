<?php

namespace App\Filament\Resources\VendorAgreements\Pages;

use App\Filament\Resources\VendorAgreements\VendorAgreementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorAgreement extends CreateRecord
{
    protected static string $resource = VendorAgreementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
