<?php

namespace App\Filament\Resources\HrReports\Pages;

use App\Filament\Resources\HrReports\HrReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHrReports extends ListRecords
{
    protected static string $resource = HrReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
