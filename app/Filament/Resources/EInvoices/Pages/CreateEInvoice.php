<?php

namespace App\Filament\Resources\EInvoices\Pages;

use App\Filament\Resources\EInvoices\EInvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEInvoice extends CreateRecord
{
    protected static string $resource = EInvoiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
