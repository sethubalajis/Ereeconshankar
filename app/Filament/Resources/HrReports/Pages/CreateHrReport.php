<?php

namespace App\Filament\Resources\HrReports\Pages;

use App\Filament\Resources\HrReports\HrReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHrReport extends CreateRecord
{
    protected static string $resource = HrReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
