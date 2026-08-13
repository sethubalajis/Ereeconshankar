<?php

namespace App\Filament\Branch\Resources\PurchaseOrderItems\Pages;

use App\Filament\Branch\Resources\PurchaseOrderItems\PurchaseOrderItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrderItem extends CreateRecord
{
    protected static string $resource = PurchaseOrderItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
