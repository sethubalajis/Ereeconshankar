<?php

namespace App\Filament\Resources\PayrollCycles\Pages;

use App\Filament\Resources\PayrollCycles\PayrollCycleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPayrollCycle extends EditRecord
{
    protected static string $resource = PayrollCycleResource::class;

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
