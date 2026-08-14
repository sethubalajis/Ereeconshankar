<?php

namespace App\Filament\Branch\Resources\PayrollProcessings\Pages;

use App\Filament\Branch\Resources\PayrollProcessings\PayrollProcessingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayrollProcessing extends CreateRecord
{
    protected static string $resource = PayrollProcessingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
