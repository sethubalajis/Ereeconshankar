<?php

namespace App\Filament\Resources\EInvoices\Pages;

use App\Filament\Resources\EInvoices\EInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEInvoices extends ListRecords
{
    protected static string $resource = EInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
