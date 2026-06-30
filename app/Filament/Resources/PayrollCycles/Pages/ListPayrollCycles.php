<?php

namespace App\Filament\Resources\PayrollCycles\Pages;

use App\Filament\Resources\PayrollCycles\PayrollCycleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayrollCycles extends ListRecords
{
    protected static string $resource = PayrollCycleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
