<?php

namespace App\Filament\Branch\Resources\SalaryStructures\Pages;

use App\Filament\Branch\Resources\SalaryStructures\SalaryStructureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalaryStructure extends CreateRecord
{
    protected static string $resource = SalaryStructureResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
