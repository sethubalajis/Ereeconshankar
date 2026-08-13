<?php

namespace App\Filament\Branch\Resources\PurchaseOrderItems\Pages;

use App\Filament\Branch\Resources\PurchaseOrderItems\PurchaseOrderItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseOrderItems extends ListRecords
{
    protected static string $resource = PurchaseOrderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
