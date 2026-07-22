<?php

namespace App\Filament\Branch\Resources\PurchaseOrders\Pages;

use App\Filament\Branch\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseOrders extends ListRecords
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
