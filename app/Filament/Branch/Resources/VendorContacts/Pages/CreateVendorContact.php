<?php

namespace App\Filament\Branch\Resources\VendorContacts\Pages;

use App\Filament\Branch\Resources\VendorContacts\VendorContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorContact extends CreateRecord
{
    protected static string $resource = VendorContactResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
