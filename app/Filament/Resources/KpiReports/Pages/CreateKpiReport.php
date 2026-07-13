<?php

namespace App\Filament\Resources\KpiReports\Pages;

use App\Filament\Resources\KpiReports\KpiReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKpiReport extends CreateRecord
{
    protected static string $resource = KpiReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
