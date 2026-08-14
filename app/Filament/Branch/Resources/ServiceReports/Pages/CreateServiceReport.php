<?php

namespace App\Filament\Branch\Resources\ServiceReports\Pages;

use App\Filament\Branch\Resources\ServiceReports\ServiceReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceReport extends CreateRecord
{
    protected static string $resource = ServiceReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
