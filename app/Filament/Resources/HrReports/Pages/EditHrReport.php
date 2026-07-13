<?php

namespace App\Filament\Resources\HrReports\Pages;

use App\Filament\Resources\HrReports\HrReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHrReport extends EditRecord
{
    protected static string $resource = HrReportResource::class;

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
