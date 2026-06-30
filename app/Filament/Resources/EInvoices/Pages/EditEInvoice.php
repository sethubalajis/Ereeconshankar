<?php

namespace App\Filament\Resources\EInvoices\Pages;

use App\Filament\Resources\EInvoices\EInvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEInvoice extends EditRecord
{
    protected static string $resource = EInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
