<?php

namespace App\Filament\Branch\Resources\BranchPerformanceReports\Pages;

use App\Filament\Branch\Resources\BranchPerformanceReports\BranchPerformanceReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchPerformanceReports extends ListRecords
{
    protected static string $resource = BranchPerformanceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
