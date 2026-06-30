<?php

namespace App\Filament\Resources\EWayBills\Pages;

use App\Filament\Resources\EWayBills\EWayBillResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEWayBill extends EditRecord
{
    protected static string $resource = EWayBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
