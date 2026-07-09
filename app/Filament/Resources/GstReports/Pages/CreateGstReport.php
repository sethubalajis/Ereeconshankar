<?php

namespace App\Filament\Resources\GstReports\Pages;

use App\Filament\Resources\GstReports\GstReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGstReport extends CreateRecord
{
    protected static string $resource = GstReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
