<?php

namespace App\Filament\Branch\Resources\PurchaseOrderItems\Pages;

use App\Filament\Branch\Resources\PurchaseOrderItems\PurchaseOrderItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrderItem extends EditRecord
{
    protected static string $resource = PurchaseOrderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
