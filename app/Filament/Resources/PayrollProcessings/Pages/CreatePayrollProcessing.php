<?php

namespace App\Filament\Resources\PayrollProcessings\Pages;

use App\Filament\Resources\PayrollProcessings\PayrollProcessingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayrollProcessing extends CreateRecord
{
    protected static string $resource = PayrollProcessingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
