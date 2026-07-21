<?php

namespace App\Filament\Branch\Resources\PurchaseRequisitions\Pages;

use App\Filament\Branch\Resources\PurchaseRequisitions\PurchaseRequisitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseRequisition extends CreateRecord
{
    protected static string $resource = PurchaseRequisitionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
