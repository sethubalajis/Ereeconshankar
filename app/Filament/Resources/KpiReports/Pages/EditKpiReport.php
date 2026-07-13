<?php

namespace App\Filament\Resources\KpiReports\Pages;

use App\Filament\Resources\KpiReports\KpiReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKpiReport extends EditRecord
{
    protected static string $resource = KpiReportResource::class;

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
