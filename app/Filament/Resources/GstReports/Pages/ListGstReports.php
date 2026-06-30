<?php

namespace App\Filament\Resources\GstReports\Pages;

use App\Filament\Resources\GstReports\GstReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGstReports extends ListRecords
{
    protected static string $resource = GstReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
