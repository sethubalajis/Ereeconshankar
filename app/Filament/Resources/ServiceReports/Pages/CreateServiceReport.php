<?php

namespace App\Filament\Resources\ServiceReports\Pages;

use App\Filament\Resources\ServiceReports\ServiceReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceReport extends CreateRecord
{
    protected static string $resource = ServiceReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
