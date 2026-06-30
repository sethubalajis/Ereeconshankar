<?php

namespace App\Filament\Resources\KpiReports\Pages;

use App\Filament\Resources\KpiReports\KpiReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKpiReports extends ListRecords
{
    protected static string $resource = KpiReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
