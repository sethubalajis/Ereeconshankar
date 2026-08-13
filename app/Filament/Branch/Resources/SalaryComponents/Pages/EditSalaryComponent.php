<?php

namespace App\Filament\Branch\Resources\SalaryComponents\Pages;

use App\Filament\Branch\Resources\SalaryComponents\SalaryComponentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSalaryComponent extends EditRecord
{
    protected static string $resource = SalaryComponentResource::class;

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
