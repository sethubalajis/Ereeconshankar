<?php

namespace App\Filament\Resources\EWayBills\Pages;

use App\Filament\Resources\EWayBills\EWayBillResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEWayBill extends CreateRecord
{
    protected static string $resource = EWayBillResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
