<?php

namespace App\Filament\Branch\Resources\PurchaseReports\Pages;

use App\Filament\Branch\Resources\PurchaseReports\PurchaseReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseReport extends CreateRecord
{
    protected static string $resource = PurchaseReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
