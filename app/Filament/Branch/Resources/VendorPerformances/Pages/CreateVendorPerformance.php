<?php

namespace App\Filament\Branch\Resources\VendorPerformances\Pages;

use App\Filament\Branch\Resources\VendorPerformances\VendorPerformanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorPerformance extends CreateRecord
{
    protected static string $resource = VendorPerformanceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
