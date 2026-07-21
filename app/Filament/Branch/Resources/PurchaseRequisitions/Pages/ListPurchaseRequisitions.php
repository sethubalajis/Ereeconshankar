<?php

namespace App\Filament\Branch\Resources\PurchaseRequisitions\Pages;

use App\Filament\Branch\Resources\PurchaseRequisitions\PurchaseRequisitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseRequisitions extends ListRecords
{
    protected static string $resource = PurchaseRequisitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
