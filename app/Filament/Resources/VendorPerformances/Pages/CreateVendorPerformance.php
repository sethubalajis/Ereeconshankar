<?php

namespace App\Filament\Resources\VendorPerformances\Pages;

use App\Filament\Resources\VendorPerformances\VendorPerformanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorPerformance extends CreateRecord
{
    protected static string $resource = VendorPerformanceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
