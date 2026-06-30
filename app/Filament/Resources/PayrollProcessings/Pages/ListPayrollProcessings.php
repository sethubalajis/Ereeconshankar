<?php

namespace App\Filament\Resources\PayrollProcessings\Pages;

use App\Filament\Resources\PayrollProcessings\PayrollProcessingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayrollProcessings extends ListRecords
{
    protected static string $resource = PayrollProcessingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
