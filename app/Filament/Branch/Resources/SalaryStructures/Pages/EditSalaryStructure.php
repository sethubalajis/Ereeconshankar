<?php

namespace App\Filament\Branch\Resources\SalaryStructures\Pages;

use App\Filament\Branch\Resources\SalaryStructures\SalaryStructureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSalaryStructure extends EditRecord
{
    protected static string $resource = SalaryStructureResource::class;

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
