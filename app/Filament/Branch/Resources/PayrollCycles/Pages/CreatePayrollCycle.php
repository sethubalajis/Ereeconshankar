<?php

namespace App\Filament\Branch\Resources\PayrollCycles\Pages;

use App\Filament\Branch\Resources\PayrollCycles\PayrollCycleResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayrollCycle extends CreateRecord
{
    protected static string $resource = PayrollCycleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
