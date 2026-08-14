<?php

namespace App\Filament\Branch\Resources\GstReports\Pages;

use App\Filament\Branch\Resources\GstReports\GstReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGstReport extends EditRecord
{
    protected static string $resource = GstReportResource::class;

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
