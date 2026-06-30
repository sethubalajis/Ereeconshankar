<?php

namespace App\Filament\Resources\ServiceReports\Pages;

use App\Filament\Resources\ServiceReports\ServiceReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceReport extends EditRecord
{
    protected static string $resource = ServiceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
