<?php

namespace App\Filament\Resources\BranchPerformanceReports\Pages;

use App\Filament\Resources\BranchPerformanceReports\BranchPerformanceReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchPerformanceReport extends EditRecord
{
    protected static string $resource = BranchPerformanceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
