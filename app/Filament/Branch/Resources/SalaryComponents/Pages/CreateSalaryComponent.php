<?php

namespace App\Filament\Branch\Resources\SalaryComponents\Pages;

use App\Filament\Branch\Resources\SalaryComponents\SalaryComponentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalaryComponent extends CreateRecord
{
    protected static string $resource = SalaryComponentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
