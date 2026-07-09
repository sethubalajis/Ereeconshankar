<?php

namespace App\Filament\Resources\PayrollProcessings\Pages;

use App\Filament\Resources\PayrollProcessings\PayrollProcessingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPayrollProcessing extends EditRecord
{
    protected static string $resource = PayrollProcessingResource::class;

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
