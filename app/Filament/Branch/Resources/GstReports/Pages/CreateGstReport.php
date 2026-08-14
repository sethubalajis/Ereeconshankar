<?php

namespace App\Filament\Branch\Resources\GstReports\Pages;

use App\Filament\Branch\Resources\GstReports\GstReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGstReport extends CreateRecord
{
    protected static string $resource = GstReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
