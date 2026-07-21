<?php

namespace App\Filament\Branch\Resources\BranchPerformanceReports\Pages;

use App\Filament\Branch\Resources\BranchPerformanceReports\BranchPerformanceReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchPerformanceReport extends CreateRecord
{
    protected static string $resource = BranchPerformanceReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
