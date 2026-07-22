<?php

namespace App\Filament\Branch\Resources\PurchaseReports\Pages;

use App\Filament\Branch\Resources\PurchaseReports\PurchaseReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseReport extends EditRecord
{
    protected static string $resource = PurchaseReportResource::class;

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
